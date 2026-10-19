<?php
namespace core;

class Controller {

    protected Response $response;
    protected Request $request;

    public function __construct(?Request $request = null, ?Response $response = null) {
        $this->request = $request ?? new Request();
        $this->response = $response ?? new Response();
    }

    /**
     * Redireciona para uma URL relativa ou absoluta.
     */
    protected function redirect(string $url, int $status = 302): void {
        $this->response->redirect($url, $status);
    }

    /**
     * Retorna uma resposta JSON.
     */
    protected function json(mixed $data, int $status = 200): void {
        $this->response->json($data, $status);
    }

    /**
     * Retorna a URL base da aplicação.
     */
    protected function getBaseUrl(): string {
        return base_url();
    }

    /**
     * Renderiza uma view com suporte a layout mestre.
     * 
     * @param string $view Caminho da view relativo a src/views (ex: 'pages/home' ou 'home')
     * @param array $data Dados disponibilizados para a view e layout
     * @param string|null $layout Nome do layout em src/views/layouts/ (ex: 'main') ou null para view sem layout
     */
    protected function view(string $view, array $data = [], ?string $layout = 'main'): void {
        $base = $this->getBaseUrl();

        // Se passar apenas 'home', busca em 'pages/home' se existir, ou 'home'
        $viewPath = __DIR__ . '/../src/views/' . $view . '.php';
        if (!file_exists($viewPath) && file_exists(__DIR__ . '/../src/views/pages/' . $view . '.php')) {
            $viewPath = __DIR__ . '/../src/views/pages/' . $view . '.php';
        }

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        extract($data);
        $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);

        if ($layout === null) {
            require $viewPath;
            return;
        }

        // Captura o conteúdo da página
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layoutPath = __DIR__ . '/../src/views/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            // Se o layout não existir, emite apenas o conteúdo
            echo $content;
        }
    }

    /**
     * Renderização de views legadas (mantém total retrocompatibilidade).
     */
    private function _render(string $folder, string $viewName, array $viewData = []): void {
        $file = __DIR__ . '/../src/views/' . $folder . '/' . $viewName . '.php';
        if (file_exists($file)) {
            extract($viewData);
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            require $file;
        }
    }

    /**
     * Renderiza um partial legado (ex: header, footer).
     */
    protected function renderPartial(string $viewName, array $viewData = []): void {
        $this->_render('partials', $viewName, $viewData);
    }

    /**
     * Renderiza uma página legada.
     */
    public function render(string $viewName, array $viewData = []): void {
        $this->_render('pages', $viewName, $viewData);
    }
}