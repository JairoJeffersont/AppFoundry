<?php

namespace App\Services;

use App\Exceptions\LoginException;
use App\Exceptions\NenhumRegistroEncontrado;

/**
 * Class AuthService
 *
 * Camada de serviço responsável por gerenciar a autenticação de usuários
 * e o ciclo de vida da sessão HTTP em PHP nativo.
 *
 * @package App\Services
 */
class AuthService {
    /**
     * Instância do serviço de usuários.
     */
    private UsuarioService $usuario_service;

    /**
     * Inicializa o serviço de autenticação e injeta a dependência do UsuarioService.
     */
    public function __construct() {
        $this->usuario_service = new UsuarioService();
    }

    /**
     * Autentica um usuário através do e-mail e da senha fornecidos.
     *
     * @param string $email E-mail cadastrado do usuário.
     * @param string $senha Senha em texto puro informada no login.
     * @return object Retorna o objeto do usuário autenticado sem o atributo de senha.
     *
     * @throws LoginException Lançada quando o e-mail não for encontrado ou a senha estiver incorreta.
     */
    public function login(string $email, string $senha): object {
        try {
            $usuario = $this->usuario_service->buscarUsuario($email, 'email');
        } catch (NenhumRegistroEncontrado $e) {
            throw new LoginException('E-mail ou senha incorretos.');
        }

        if (!$usuario || !password_verify($senha, $usuario->senha)) {
            throw new LoginException('E-mail ou senha incorretos.');
        }

        unset($usuario->senha);

        return $usuario;
    }

    /**
     * Armazena as informações essenciais do usuário autenticado na sessão HTTP.
     *
     * Regenera o ID da sessão ativa para prevenir ataques do tipo Session Fixation.
     *
     * @param object $usuario Instância do usuário retornada pelo método de login.
     * @return void
     */
    public function criarSessao(object $usuario): void {
        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id'    => $usuario->id,
            'nome'  => $usuario->nome,
            'email' => $usuario->email,
        ];
    }

    /**
     * Verifica se existe um usuário autenticado ativo na sessão.
     *
     * @return bool Retorna true se houver um ID de usuário na sessão, caso contrário false.
     */
    public function usuarioAutenticado(): bool {
        return isset($_SESSION['usuario']['id']);
    }

    /**
     * Obtém os dados do usuário atualmente armazenados na sessão.
     *
     * @return array<string, mixed>|null Retorna o array de dados do usuário ou null se não estiver autenticado.
     */
    public function usuario(): ?array {
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Encerra a sessão atual do usuário.
     *
     * Limpa as variáveis da superglobal $_SESSION, invalida o cookie de sessão do navegador
     * e destrói o arquivo de sessão mantido no servidor.
     *
     * @return void
     */
    public function encerrarSessao(): void {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
