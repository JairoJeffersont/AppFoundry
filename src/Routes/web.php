<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UsuarioController;
use App\Middlewares\AuthMiddleware;
use Slim\App;

return function (App $app) {
        $app->get('/login', [AuthController::class, 'index']);
        $app->post('/login', [AuthController::class, 'login']);
        $app->get('/logout', [AuthController::class, 'logout']);

        $app->get('/novo-usuario', [UsuarioController::class, 'index']);
        $app->post('/novo-usuario', [UsuarioController::class, 'novoUsuario']);

        $app->group('', function ($group) {
                $group->get('/home', [HomeController::class, 'index']);
        })->add(AuthMiddleware::class);
};
