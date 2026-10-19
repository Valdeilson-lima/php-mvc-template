<?php
namespace src\controllers;

use core\Controller;

class HomeController extends Controller {

    public function index() {
        $this->view('home', ['title' => 'Início']);
    }

    public function sobre() {
        $this->view('sobre', ['title' => 'Sobre o Projeto']);
    }

    public function sobreP($args) {
        $this->json([
            'status' => 'success',
            'params' => $args
        ]);
    }
}