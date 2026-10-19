<?php
namespace core;

interface Middleware {
    /**
     * Intercepta a requisição.
     * 
     * @param Request $request
     * @param Response $response
     * @return bool Retorne true para permitir a execução, ou interrompa usando $response e retorne false.
     */
    public function handle(Request $request, Response $response): bool;
}
