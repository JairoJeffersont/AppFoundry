<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middlewares\AuthMiddleware;
use Slim\App;

return function (App $app) {
        $app->get('/login', [AuthController::class, 'index']);
        $app->post('/login', [AuthController::class, 'login']);
        $app->get('/logout', [AuthController::class, 'logout']);

        $app->group('', function ($group) {
                $group->get('/home', [HomeController::class, 'index']);
                //OUTRAS ROTAS PROTEGIDASS
        })->add(AuthMiddleware::class);
};
