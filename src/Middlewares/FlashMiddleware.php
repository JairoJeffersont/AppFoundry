<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class FlashMiddleware implements MiddlewareInterface {

    private Twig $twig;

    public function __construct(Twig $twig) {
        $this->twig = $twig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {

        $flash = $_SESSION['flash'] ?? null;

        unset($_SESSION['flash']);

        $this->twig->getEnvironment()->addGlobal('flash', $flash);

        return $handler->handle($request);
    }
}
