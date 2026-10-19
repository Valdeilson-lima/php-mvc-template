<?php
namespace src;

class Config {
    const BASE_DIR = ''; // Opcional: agora auto-detectado dinamicamente via base_url()

    const DB_DRIVER = 'mysql';
    const DB_HOST = '127.0.0.1';
    const DB_DATABASE = 'delivery';
    const DB_USER = 'root';
    const DB_PASS = '';

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';

    public static function get(string $key, mixed $default = null): mixed {
        return env($key, $default);
    }
}