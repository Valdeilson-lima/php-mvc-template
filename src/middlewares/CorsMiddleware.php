<?php
namespace src\middlewares;

use core\Middleware;
use core\Request;
use core\Response;

class CorsMiddleware implements Middleware {

    public function handle(Request $request, Response $response): bool {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';

        header("Access-Control-Allow-Origin: {$origin}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, X-Requested-With, Origin, X-CSRF-TOKEN');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $response->status(200)->send('');
            return false;
        }

        return true;
    }
}
