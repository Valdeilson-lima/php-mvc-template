<?php
namespace core;

class Request {

    protected array $data = [];
    protected array $query = [];
    protected array $files = [];
    protected array $server = [];
    protected ?array $jsonBody = null;

    public function __construct() {
        $this->server = $_SERVER;
        $this->query = $_GET;
        $this->files = $_FILES;

        // Decodifica corpo JSON caso enviado
        if ($this->isJson()) {
            $raw = file_get_contents('php://input');
            $this->jsonBody = json_decode($raw, true) ?? [];
        }

        $this->data = array_merge(
            $this->query,
            $_POST,
            $this->jsonBody ?? []
        );
    }

    /**
     * Retorna a URL normalizada da requisição (compatível com Apache, Nginx e servidor embutido PHP).
     */
    public static function getUrl(): string {
        if (!empty($_GET['request'])) {
            $url = $_GET['request'];
        } else {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $path = parse_url($uri, PHP_URL_PATH) ?? '/';

            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            if ($scriptDir !== '/' && $scriptDir !== '\\' && $scriptDir !== '.') {
                if (str_starts_with($path, $scriptDir)) {
                    $path = substr($path, strlen($scriptDir));
                }
            }
            $url = $path;
        }

        $url = trim((string)$url, '/');
        return '/' . $url;
    }

    /**
     * Retorna o método HTTP (suporta spoofing via _method e X-HTTP-Method-Override).
     */
    public static function getMethod(): string {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'POST') {
            if (isset($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $method = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            }
        }

        return strtolower($method);
    }

    /**
     * Verifica se o corpo da requisição é JSON.
     */
    public function isJson(): bool {
        $contentType = $this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '';
        return str_contains(strtolower($contentType), 'application/json');
    }

    /**
     * Retorna todos os dados da requisição.
     */
    public function all(): array {
        return $this->data;
    }

    /**
     * Retorna um campo de entrada com fallback para valor padrão.
     */
    public function input(string $key, mixed $default = null): mixed {
        return $this->data[$key] ?? $default;
    }

    /**
     * Retorna apenas os campos informados.
     */
    public function only(array $keys): array {
        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $this->data)) {
                $result[$key] = $this->data[$key];
            }
        }
        return $result;
    }

    /**
     * Retorna todos os campos exceto os informados.
     */
    public function except(array $keys): array {
        $result = $this->data;
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        return $result;
    }

    /**
     * Retorna parâmetro da Query String ($_GET).
     */
    public function query(?string $key = null, mixed $default = null): mixed {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    /**
     * Retorna arquivo enviado via upload ($_FILES).
     */
    public function file(string $key): ?array {
        return $this->files[$key] ?? null;
    }

    /**
     * Retorna o endereço IP do cliente.
     */
    public function ip(): string {
        return $this->server['HTTP_CLIENT_IP']
            ?? $this->server['HTTP_X_FORWARDED_FOR']
            ?? $this->server['REMOTE_ADDR']
            ?? '127.0.0.1';
    }

    /**
     * Retorna um cabeçalho HTTP da requisição.
     */
    public function header(string $key, mixed $default = null): mixed {
        $normalized = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$normalized] ?? $this->server[strtoupper($key)] ?? $default;
    }
}