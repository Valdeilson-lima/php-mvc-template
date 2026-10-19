<?php
namespace core;

class Csrf {
    
    public static function getToken(): string {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    public static function validate(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        $sessionToken = $_SESSION['_csrf_token'] ?? '';
        if (empty($sessionToken) || empty($token)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function regenerate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf_token'];
    }
}
