<?php

namespace App\Controllers;

use App\Exceptions\RegistroDuplicadoException;
use App\Services\UsuarioService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use JairoJeffersont\EasyLogger\Logger;


class UsuarioController {

    private UsuarioService $usuario_service;

    public function __construct() {
        $this->usuario_service = new UsuarioService();
    }

    public function index(Request $request, Response $response): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/usuario/novo-usuario.twig');
    }

    public function novoUsuario(Request $request, Response $response): Response {
        $dados = $request->getParsedBody();
        try {
            $this->usuario_service->criarUsuario($dados);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuário cadastrado com sucesso. Faça login'];
            return $response->withHeader('Location', '/login')->withStatus(302);
        } catch (RegistroDuplicadoException $e) {
            $_SESSION['flash'] = ['type' => 'info', 'message' => $e->getMessage()];
            return $response->withHeader('Location', '/novo-usuario')->withStatus(302);
        } catch (Exception $e) {
            $log_id = Logger::newLog(LOG_FOLDER, 'ERROR', $e->getMessage(), 'ERROR');
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Erro interno do servidor | ' . $log_id];
            return $response->withHeader('Location', '/novo-usuario')->withStatus(302);
        }
    }
}
