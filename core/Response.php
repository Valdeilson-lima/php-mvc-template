<?php
namespace core;

class Response {

    protected int $statusCode = 200;
    protected array $headers = [];

    /**
     * Define o código de status HTTP da resposta.
     */
    public function status(int $code): self {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Adiciona um cabeçalho HTTP à resposta.
     */
    public function header(string $name, string $value): self {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Envia os cabeçalhos registrados e define o status code.
     */
    protected function sendHeaders(): void {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
    }

    /**
     * Retorna uma resposta JSON formatada.
     */
    public function json(mixed $data, int $status = 200): void {
        $this->status($status);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->sendHeaders();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Envia texto ou HTML como resposta.
     */
    public function send(string $content, int $status = 200): void {
        $this->status($status);
        $this->sendHeaders();
        echo $content;
        exit;
    }

    /**
     * Redireciona para uma URL externa ou interna.
     */
    public function redirect(string $url, int $status = 302): void {
        $target = str_starts_with($url, 'http://') || str_starts_with($url, 'https://')
            ? $url
            : base_url($url);

        $this->status($status);
        $this->header('Location', $target);
        $this->sendHeaders();
        exit;
    }
}
