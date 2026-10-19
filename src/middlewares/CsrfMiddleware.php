<?php
namespace src\middlewares;

use core\Csrf;
use core\Middleware;
use core\Request;
use core\Response;

class CsrfMiddleware implements Middleware {

    public function handle(Request $request, Response $response): bool {
        $method = Request::getMethod();

        // Métodos seguros (GET, HEAD, OPTIONS) não requerem verificação CSRF
        if (in_array($method, ['get', 'head', 'options'], true)) {
            return true;
        }

        // Rotas de API que utilizam Authorization ou prefixo /api/ são isentas de CSRF
        if (str_starts_with(Request::getUrl(), '/api/') || !empty($request->header('Authorization'))) {
            return true;
        }

        $token = $request->input('_csrf')
            ?? $request->header('X-CSRF-TOKEN')
            ?? $request->header('X-XSRF-TOKEN');

        if (!Csrf::validate($token)) {
            if ($request->isJson()) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Token CSRF inválido ou expirado.'
                ], 419);
                return false;
            }

            flash('error', 'Sua sessão expirou ou o formulário é inválido. Tente novamente.');
            $response->status(419)->send('<h1>419 - Página Expirada</h1><p>Token CSRF inválido ou ausente. Retorne e atualize a página.</p>');
            return false;
        }

        return true;
    }
}
