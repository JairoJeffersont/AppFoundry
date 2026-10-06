<?php

namespace App\Controllers;

use App\Exceptions\LoginException;
use App\Services\AuthService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use JairoJeffersont\EasyLogger\Logger;

/**
 * Class AuthController
 *
 * Controller responsável pelo gerenciamento de autenticação do usuário,
 * exibição de formulários de login e controle de fluxo de sessões na aplicação.
 *
 * @package App\Controllers
 */
class AuthController {
    /**
     * Instância do serviço de autenticação.
     */
    private AuthService $auth_service;

    /**
     * Inicializa o controller de autenticação e instancia o AuthService.
     */
    public function __construct() {
        $this->auth_service = new AuthService();
    }

    /**
     * Exibe a tela de login.
     *
     * @param Request $request Objeto de requisição HTTP PSR-7.
     * @param Response $response Objeto de resposta HTTP PSR-7.
     * @return Response Resposta HTTP contendo a view Twig renderizada.
     */
    public function index(Request $request, Response $response): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/auth/login.twig');
    }

    /**
     * Processa a tentativa de autenticação do usuário.
     *
     * Valida os dados informados no formulário, efetua o login no AuthService,
     * inicializa a sessão HTTP e redireciona o usuário.
     *
     * @param Request $request Objeto de requisição HTTP PSR-7 contendo os dados do formulário.
     * @param Response $response Objeto de resposta HTTP PSR-7.
     * @return Response Redirecionamento HTTP (302) para '/home' em caso de sucesso ou '/login' em caso de falha.
     */
    public function login(Request $request, Response $response): Response {
        $dados = $request->getParsedBody();

        $email = trim($dados['email'] ?? '');
        $senha = $dados['senha'] ?? '';

        try {
            $usuario = $this->auth_service->login($email, $senha);
            $this->auth_service->criarSessao($usuario);
            return $response->withHeader('Location', '/home')->withStatus(302);
        } catch (LoginException $e) {
            $_SESSION['flash'] = ['type' => 'info', 'message' => $e->getMessage()];
            return $response->withHeader('Location', '/login')->withStatus(302);
        } catch (Exception $e) {
            $log_id = Logger::newLog(LOG_FOLDER, 'ERROR', $e->getMessage(), 'ERROR');
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Erro interno do servidor | ' . $log_id];
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    }

    /**
     * Encerra a sessão do usuário autenticado.
     *
     * @param Request $request Objeto de requisição HTTP PSR-7.
     * @param Response $response Objeto de resposta HTTP PSR-7.
     * @return Response Redirecionamento HTTP (302) para a tela de login.
     */
    public function logout(Request $request, Response $response): Response {
        $this->auth_service->encerrarSessao();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
