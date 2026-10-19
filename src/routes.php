<?php
use core\Router;

/** @var Router $router */
$router = new Router();

// Rotas Web Principais
$router->get('/', 'HomeController@index');
$router->get('/sobre', 'HomeController@sobre');
$router->get('/sobre/{nome}', 'HomeController@sobreP');

// Grupo de Rotas de API (com respostas JSON e Middlewares)
$router->group(['prefix' => '/api'], function(Router $router) {
    $router->get('/info', function($args, $request, $response) {
        $response->json([
            'app' => env('APP_NAME', 'Delivery MVC'),
            'version' => '2.0.0',
            'status' => 'operational',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    });

    $router->get('/ping', function($args, $request, $response) {
        $response->json(['pong' => true]);
    });
});