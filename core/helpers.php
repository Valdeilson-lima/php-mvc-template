<?php

if (!function_exists('env')) {
    /**
     * Recupera o valor de uma variável de ambiente com valor padrão.
     */
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        switch (strtolower((string)$value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('base_url')) {
    /**
     * Retorna a URL base do projeto dinamicamente.
     */
    function base_url($path = '') {
        $path = ltrim((string)$path, '/');

        // Se APP_URL estiver explicitamente configurada e não for padrão local em CLI
        $configuredUrl = env('APP_URL');

        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];

            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            $scriptDir = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : str_replace('\\', '/', $scriptDir);

            $base = $scheme . '://' . $host . $scriptDir;
            return rtrim($base, '/') . ($path ? '/' . $path : '');
        }

        if (!empty($configuredUrl)) {
            return rtrim($configuredUrl, '/') . ($path ? '/' . $path : '');
        }

        return '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Retorna a URL de um ativo estático (CSS, JS, Imagens) em public/assets.
     */
    function asset($path) {
        return base_url('assets/' . ltrim((string)$path, '/'));
    }
}

if (!function_exists('e')) {
    /**
     * Escapa caracteres especiais para evitar ataques XSS.
     */
    function e($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('flash')) {
    /**
     * Define ou recupera uma mensagem flash da sessão.
     */
    function flash($key, $message = null) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        if ($message === null) {
            if (isset($_SESSION['_flash'][$key])) {
                $msg = $_SESSION['_flash'][$key];
                unset($_SESSION['_flash'][$key]);
                return $msg;
            }
            return null;
        }

        $_SESSION['_flash'][$key] = $message;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Retorna o token CSRF atual.
     */
    function csrf_token() {
        return \core\Csrf::getToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Retorna o input HTML oculto com o token CSRF.
     */
    function csrf_field() {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}
