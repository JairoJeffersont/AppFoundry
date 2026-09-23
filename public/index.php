<?php

use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$bootEloquent = require __DIR__ . '/../config/database.php';
$bootEloquent();

$app = AppFactory::create();

$twigConfig = require __DIR__ . '/../config/twig.php';
$twig = $twigConfig();
$app->add(TwigMiddleware::create($app, $twig));

$routes = require __DIR__ . '/../src/Routes/web.php';
$routes($app);

$app->run();
