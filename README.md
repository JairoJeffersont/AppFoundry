# AppFoundry

Aplicação PHP de exemplo com autenticação e cadastro de usuários, construída com [Slim Framework 4](https://www.slimframework.com/), [Eloquent ORM](https://laravel.com/docs/eloquent) e [Twig](https://twig.symfony.com/). O projeto também usa PHP-Dotenv para configuração por ambiente e Easy Logger para registrar erros da aplicação.

## Requisitos

- PHP compatível com as dependências instaladas pelo Composer, com PDO e o driver do banco escolhido habilitados.
- Composer.
- MySQL (ou outro banco compatível com a configuração do Eloquent).

## Instalação

```bash
git clone https://github.com/JairoJeffersont/AppFoundry nome-do-projeto
cd nome-do-projeto
composer install
cp .env.example .env
```

Edite o `.env` com os dados da sua aplicação e do banco. As variáveis reconhecidas são:

```dotenv
APP_ENV=development
APP_NAME="Minha aplicação"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

O arquivo `config/database.php` usa esses valores para configurar o Eloquent; se não forem informados, aplica os padrões mostrados acima.

## Banco de dados

Crie o banco indicado por `DB_DATABASE` e importe [`database.sql`](database.sql). O script cria a tabela `usuarios`, com `id`, `nome`, `email`, `senha`, `created_at` e `updated_at`, e insere uma conta de demonstração:

| E-mail | Senha |
| --- | --- |
| `exemplo@usuario.com` | `senha123` |

Com MySQL e as configurações padrão:

```bash
mysql -u root -p web < database.sql
```

O model `App\Models\Usuario` usa a tabela `usuarios`. Ao atribuir uma senha em texto puro ao atributo `senha`, o model a transforma com `password_hash`; no login, o serviço valida a senha usando `password_verify`.

## Executar localmente

Na raiz do projeto, inicie o servidor PHP com `public` como document root:

```bash
php -S localhost:8000 -t public
```

Acesse <http://localhost:8000/login>. O formulário de login já vem preenchido com as credenciais de demonstração. Também é possível criar outra conta em `/novo-usuario`.

## Rotas disponíveis

| Método | Caminho | Acesso | Comportamento |
| --- | --- | --- | --- |
| `GET` | `/login` | Público | Exibe o formulário de login. |
| `POST` | `/login` | Público | Autentica e inicia uma sessão; em caso de sucesso, redireciona para `/home`. |
| `GET` | `/logout` | Público | Encerra a sessão e redireciona para `/login`. |
| `GET` | `/novo-usuario` | Público | Exibe o formulário de cadastro. |
| `POST` | `/novo-usuario` | Público | Cadastra um usuário e redireciona para `/login`; e-mails já cadastrados são recusados. |
| `GET` | `/home` | Autenticado | Exibe a página inicial; sem sessão, redireciona para `/login`. |

Mensagens de sucesso e erro são armazenadas na sessão e exibidas uma única vez nas páginas que incluem o componente de alertas. Erros inesperados são registrados na pasta `logs/`; a mensagem apresentada ao usuário inclui o identificador do log.

## Organização do projeto

```text
config/                  Inicialização do banco, Twig e tratamento de erros
database.sql             Criação da tabela usuarios e usuário de demonstração
public/index.php         Bootstrap da aplicação Slim
public/.htaccess          Reescrita de URLs para o front controller (Apache)
src/Controllers/         Controllers de autenticação, usuários e página inicial
src/Exceptions/          Exceções de domínio para autenticação e usuários
src/Middlewares/         Proteção de rota e mensagens flash
src/Models/               Model Eloquent Usuario
src/Routes/web.php        Definição das rotas HTTP
src/Services/             Regras de autenticação e operações de usuário
src/Views/                Templates Twig, layouts e alertas
logs/                     Arquivos de log da aplicação
```

O `public/index.php` inicia a sessão, carrega o `.env`, inicializa o Eloquent e o Twig, registra middlewares e rotas e então executa a aplicação. O `AuthMiddleware` protege `/home`; após login, apenas o ID, nome e e-mail do usuário são guardados na sessão, cujo identificador é regenerado. O encerramento do login limpa os dados e destrói a sessão.

O `UsuarioService` também implementa métodos de listagem paginada, busca, atualização e exclusão. Nesta versão, essas operações não possuem rotas HTTP associadas.

## Hospedagem

Configure o servidor web para usar `public/` como document root e encaminhar URLs não correspondentes a arquivos para `public/index.php`. O arquivo `public/.htaccess` já contém a regra de reescrita para Apache com `mod_rewrite`; em outros servidores, configure a regra equivalente. Consulte a [documentação do Slim sobre servidores web](https://www.slimframework.com/docs/v4/start/web-servers.html).

Antes de publicar, configure as credenciais de produção no `.env`, defina `APP_ENV=production`, remova ou altere a conta de demonstração e garanta que `logs/` e o diretório de cache `storage/cache/twig` possam ser gravados pelo processo PHP. Não exponha o arquivo `.env` nem use as credenciais de exemplo em produção.

## Licença

Este projeto está disponível sob a licença MIT.
