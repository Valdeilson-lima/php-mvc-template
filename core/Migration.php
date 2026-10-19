<?php
namespace core;

abstract class Migration {
    /**
     * Executa as alterações de banco de dados.
     */
    abstract public function up(\PDO $pdo): void;

    /**
     * Reverte as alterações de banco de dados.
     */
    abstract public function down(\PDO $pdo): void;
}
