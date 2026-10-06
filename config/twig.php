<?php

use Slim\Views\Twig;

return function () {
    $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';

    //CRIAR O CACHE DO TWIG QUANDO EM PRODUÇAO
    $twig = Twig::create(__DIR__ . '/../src/Views', [
        'cache' => $isProduction ? __DIR__ . '/../storage/cache/twig' : false,
        'debug' => !$isProduction,
    ]);

    $twig->getEnvironment()->addGlobal('app_name', $_ENV['APP_NAME'] ?? '');
    $twig->getEnvironment()->addGlobal('usuario_sessao', $_SESSION['usuario'] ?? null);

    return $twig;
};
