<?php
namespace core;

use src\Config;

class Database {

    private static ?\PDO $_pdo = null;

    public static function getInstance(): \PDO {
        if (self::$_pdo === null) {
            $driver = env('DB_DRIVER', Config::DB_DRIVER ?? 'mysql');
            $host = env('DB_HOST', Config::DB_HOST ?? '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $database = env('DB_DATABASE', Config::DB_DATABASE ?? 'delivery');
            $user = env('DB_USER', Config::DB_USER ?? 'root');
            $pass = env('DB_PASS', Config::DB_PASS ?? '');
            $charset = env('DB_CHARSET', 'utf8mb4');

            $dsn = "{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}";

            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$_pdo = new \PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                if (env('APP_DEBUG', true)) {
                    throw new \RuntimeException("Erro de Conexão com Banco de Dados: " . $e->getMessage(), (int)$e->getCode(), $e);
                }
                throw new \RuntimeException("Erro ao conectar com a base de dados.");
            }
        }

        return self::$_pdo;
    }

    private function __construct() { }
    private function __clone() { }
    public function __wakeup() { }
}