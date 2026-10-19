<?php

// Inicializa autoload do Composer
require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente do .env se existir
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

// Configura tratamento e exibição de erros com base no ambiente
if (env('APP_DEBUG', true)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Inicializa sessão com boas práticas de segurança
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Carrega as rotas da aplicação
require __DIR__ . '/../src/routes.php';

// Executa o roteador
$router->run($router->routes);