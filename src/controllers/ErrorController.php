<?php
namespace src\controllers;

use core\Controller;

class ErrorController extends Controller {

    public function index() {
        $this->view('404', ['title' => '404 - Página Não Encontrada']);
    }
}