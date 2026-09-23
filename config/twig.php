<?php

use Slim\Views\Twig;

return function () {
    $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';

    $twig = Twig::create(__DIR__ . '/../src/Views', [
        'cache' => $isProduction ? __DIR__ . '/../storage/cache/twig' : false,
        'debug' => !$isProduction,
    ]);

    $twig->getEnvironment()->addGlobal('app_name', $_ENV['APP_NAME'] ?? '');

    return $twig;
};
