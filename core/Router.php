<?php
namespace core;

class Router extends RouterBase {

    protected string $groupPrefix = '';
    protected array $groupMiddlewares = [];

    /**
     * Define um grupo de rotas com prefixo e/ou middlewares compartilhados.
     */
    public function group(array $attributes, callable $callback): void {
        $previousPrefix = $this->groupPrefix;
        $previousMiddlewares = $this->groupMiddlewares;

        if (isset($attributes['prefix'])) {
            $prefix = '/' . trim($attributes['prefix'], '/');
            $this->groupPrefix = $previousPrefix . ($prefix === '/' ? '' : $prefix);
        }

        if (isset($attributes['middleware'])) {
            $middlewares = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            $this->groupMiddlewares = array_merge($previousMiddlewares, $middlewares);
        }

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddlewares = $previousMiddlewares;
    }

    /**
     * Adiciona uma rota ao registro interno.
     */
    protected function addRoute(string $method, string $endpoint, mixed $handler, array $middlewares = []): void {
        $endpoint = '/' . trim($this->groupPrefix . '/' . trim($endpoint, '/'), '/');
        if ($endpoint !== '/') {
            $endpoint = rtrim($endpoint, '/');
        }

        $allMiddlewares = array_merge($this->groupMiddlewares, $middlewares);

        $this->routes[strtolower($method)][] = [
            'endpoint' => $endpoint,
            'handler' => $handler,
            'middlewares' => $allMiddlewares
        ];
    }

    public function get(string $endpoint, mixed $trigger, array $middlewares = []): void {
        $this->addRoute('get', $endpoint, $trigger, $middlewares);
    }

    public function post(string $endpoint, mixed $trigger, array $middlewares = []): void {
        $this->addRoute('post', $endpoint, $trigger, $middlewares);
    }

    public function put(string $endpoint, mixed $trigger, array $middlewares = []): void {
        $this->addRoute('put', $endpoint, $trigger, $middlewares);
    }

    public function patch(string $endpoint, mixed $trigger, array $middlewares = []): void {
        $this->addRoute('patch', $endpoint, $trigger, $middlewares);
    }

    public function delete(string $endpoint, mixed $trigger, array $middlewares = []): void {
        $this->addRoute('delete', $endpoint, $trigger, $middlewares);
    }

    public function any(string $endpoint, mixed $trigger, array $middlewares = []): void {
        foreach (['get', 'post', 'put', 'patch', 'delete'] as $method) {
            $this->addRoute($method, $endpoint, $trigger, $middlewares);
        }
    }
}