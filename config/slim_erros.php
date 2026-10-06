<?php

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

return function ($app) {

    $errorMiddleware = $app->addErrorMiddleware(
        $_ENV['APP_ENV'] !== 'production',
        true,
        true
    );

    $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        function (
            ServerRequestInterface $request,
            Throwable $exception,
            bool $displayErrorDetails
        ): ResponseInterface {

            $_SESSION['flash'] = [
                'tipo' => 'info',
                'mensagem' => 'Página não encontrada'
            ];

            return (new Response())
                ->withHeader('Location', '/home')
                ->withStatus(302);
        }
    );

    return $errorMiddleware;
};
