<?php

namespace App\Middlewares;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthMiddleware
 *
 * Middleware responsável por interceptar as requisições HTTP e garantir
 * que apenas usuários autenticados na sessão possam acessar a rota solicitada.
 *
 * @package App\Middlewares
 */
class AuthMiddleware implements MiddlewareInterface {
    /**
     * Processa a requisição HTTP recebida.
     *
     * Verifica a existência de um ID de usuário na superglobal $_SESSION.
     * Caso esteja autenticado, permite que a requisição siga no pipeline.
     * Caso contrário, interrompe a execução e redireciona para a página de login.
     *
     * @param ServerRequestInterface $request Objeto de requisição HTTP PSR-7.
     * @param RequestHandlerInterface $handler Manipulador do próximo middleware ou rota no pipeline PSR-15.
     * @return ResponseInterface Resposta HTTP tratada (302 Redirect ou resposta do próximo handler).
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {

        if (!isset($_SESSION['usuario']['id'])) {
            $response = new Response();
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'Faça login.'];
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        return $handler->handle($request);
    }
}
