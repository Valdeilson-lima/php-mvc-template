<?php
namespace src\middlewares;

use core\Middleware;
use core\Request;
use core\Response;

class AuthMiddleware implements Middleware {

    public function handle(Request $request, Response $response): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se há identificação de autenticação na sessão
        if (empty($_SESSION['user']) && empty($_SESSION['user_id'])) {
            if ($request->isJson() || str_starts_with(Request::getUrl(), '/api/')) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Não autorizado. Faça login para continuar.'
                ], 401);
                return false;
            }

            flash('error', 'Por favor, faça login para acessar esta página.');
            $response->redirect('/login');
            return false;
        }

        return true;
    }
}
