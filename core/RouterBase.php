<?php
namespace core;

use src\Config;

class RouterBase {

    public array $routes = [];

    /**
     * Executa o roteamento, disparando middlewares e o manipulador correspondente.
     */
    public function run(?array $routes = null): void {
        $routes = $routes ?? $this->routes;
        $method = Request::getMethod();
        $url = Request::getUrl();

        $request = new Request();
        $response = new Response();

        $matchedRoute = null;

        if (isset($routes[$method])) {
            foreach ($routes[$method] as $key => $routeData) {
                // Suporte ao formato legado: $routes['get']['/url'] = 'Controller@action'
                $endpoint = is_array($routeData) ? $routeData['endpoint'] : $key;
                $handler = is_array($routeData) ? $routeData['handler'] : $routeData;
                $middlewares = is_array($routeData) ? ($routeData['middlewares'] ?? []) : [];

                // Normaliza rota e extrai parâmetros {id}, {slug}, etc.
                preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $endpoint, $paramMatches);
                $paramNames = $paramMatches[1] ?? [];

                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $endpoint);

                if (preg_match('#^' . $pattern . '$#i', $url, $matches)) {
                    array_shift($matches);

                    $args = [];
                    if (!empty($paramNames) && count($paramNames) === count($matches)) {
                        $args = array_combine($paramNames, $matches);
                    }

                    $matchedRoute = [
                        'handler' => $handler,
                        'middlewares' => $middlewares,
                        'args' => $args
                    ];
                    break;
                }
            }
        }

        if ($matchedRoute !== null) {
            // Executa Middlewares associados
            foreach ($matchedRoute['middlewares'] as $middleware) {
                $instance = is_string($middleware) ? new $middleware() : $middleware;
                if ($instance instanceof Middleware) {
                    $proceed = $instance->handle($request, $response);
                    if ($proceed === false) {
                        return;
                    }
                }
            }

            $handler = $matchedRoute['handler'];

            // Manipulador Closure / Função anônima
            if (is_callable($handler)) {
                call_user_func($handler, $matchedRoute['args'], $request, $response);
                return;
            }

            // Manipulador Controller@action
            if (is_string($handler)) {
                $parts = explode('@', $handler);
                $controllerName = $parts[0];
                $action = $parts[1] ?? Config::DEFAULT_ACTION;

                $controllerClass = str_starts_with($controllerName, '\\')
                    ? $controllerName
                    : "\\src\\controllers\\{$controllerName}";

                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass($request, $response);
                    if (method_exists($controllerInstance, $action)) {
                        $controllerInstance->$action($matchedRoute['args'], $request, $response);
                        return;
                    }
                }
            }
        }

        // Fallback 404 Não Encontrado
        $this->handleNotFound($request, $response);
    }

    /**
     * Responde com erro 404 de forma inteligente (JSON para APIs ou HTML para Web).
     */
    protected function handleNotFound(Request $request, Response $response): void {
        $errorController = "\\src\\controllers\\" . Config::ERROR_CONTROLLER;
        $defaultAction = Config::DEFAULT_ACTION;

        if (class_exists($errorController)) {
            $controller = new $errorController($request, $response);
            if (method_exists($controller, $defaultAction)) {
                $response->status(404);
                $controller->$defaultAction();
                return;
            }
        }

        if ($request->isJson() || str_starts_with(Request::getUrl(), '/api/')) {
            $response->json([
                'status' => 'error',
                'message' => 'Endpoint não encontrado (404)'
            ], 404);
        }

        $response->status(404)->send('<h1>404 - Página Não Encontrada</h1><p>A página requisitada não foi localizada.</p>');
    }
}