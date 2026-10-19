<?php
namespace core;

class MigrationRunner {

    protected \PDO $pdo;
    protected string $migrationsDir;

    public function __construct(?string $migrationsDir = null) {
        $this->pdo = Database::getInstance();
        $this->migrationsDir = $migrationsDir ?? dirname(__DIR__) . '/database/migrations';
    }

    /**
     * Garante que a tabela de controle de migrações existe.
     */
    protected function ensureMigrationsTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS _migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->pdo->exec($sql);
    }

    /**
     * Executa todas as migrações pendentes.
     */
    public function migrate(callable $logger = null): int {
        $this->ensureMigrationsTable();

        $executedStmt = $this->pdo->query("SELECT migration FROM _migrations");
        $executed = $executedStmt->fetchAll(\PDO::FETCH_COLUMN);

        $batchStmt = $this->pdo->query("SELECT MAX(batch) FROM _migrations");
        $currentBatch = (int)$batchStmt->fetchColumn() + 1;

        $files = glob($this->migrationsDir . '/*.php');
        sort($files);

        $count = 0;
        foreach ($files as $file) {
            $name = basename($file, '.php');

            if (!in_array($name, $executed, true)) {
                if ($logger) {
                    $logger("Executando: {$name}...");
                }

                $migration = require $file;
                if ($migration instanceof Migration) {
                    $this->pdo->beginTransaction();
                    try {
                        $migration->up($this->pdo);

                        $insertStmt = $this->pdo->prepare("INSERT INTO _migrations (migration, batch) VALUES (:name, :batch)");
                        $insertStmt->execute(['name' => $name, 'batch' => $currentBatch]);

                        $this->pdo->commit();
                        $count++;

                        if ($logger) {
                            $logger("✔ Sucesso: {$name}");
                        }
                    } catch (\Throwable $e) {
                        $this->pdo->rollBack();
                        throw $e;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Reverte o último lote de migrações.
     */
    public function rollback(callable $logger = null): int {
        $this->ensureMigrationsTable();

        $batchStmt = $this->pdo->query("SELECT MAX(batch) FROM _migrations");
        $lastBatch = (int)$batchStmt->fetchColumn();

        if ($lastBatch === 0) {
            return 0;
        }

        $stmt = $this->pdo->prepare("SELECT migration FROM _migrations WHERE batch = :batch ORDER BY id DESC");
        $stmt->execute(['batch' => $lastBatch]);
        $migrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $count = 0;
        foreach ($migrations as $name) {
            $file = $this->migrationsDir . '/' . $name . '.php';
            if (file_exists($file)) {
                if ($logger) {
                    $logger("Revertendo: {$name}...");
                }

                $migration = require $file;
                if ($migration instanceof Migration) {
                    $this->pdo->beginTransaction();
                    try {
                        $migration->down($this->pdo);

                        $deleteStmt = $this->pdo->prepare("DELETE FROM _migrations WHERE migration = :name");
                        $deleteStmt->execute(['name' => $name]);

                        $this->pdo->commit();
                        $count++;

                        if ($logger) {
                            $logger("✔ Revertido: {$name}");
                        }
                    } catch (\Throwable $e) {
                        $this->pdo->rollBack();
                        throw $e;
                    }
                }
            }
        }

        return $count;
    }
}
