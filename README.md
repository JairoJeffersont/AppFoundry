# AppFoundry

Esqueleto básico para iniciar novos projetos PHP com [Slim Framework](https://www.slimframework.com/), [Eloquent ORM](https://laravel.com/docs/eloquent) e [Twig](https://twig.symfony.com/).

O projeto fornece uma estrutura inicial com roteamento, controllers, views, configuração de banco de dados e carregamento de variáveis de ambiente.

## Instalação

### Via Composer

```bash
composer create-project jairo/appfoundry nome-do-projeto
cd nome-do-projeto
```

Links do pacote e do repositório serão adicionados aqui:

- Composer: `LINK_DO_COMPOSER`
- GitHub: `LINK_DO_GITHUB`

### Clonando o repositório

```bash
git clone LINK_DO_GITHUB nome-do-projeto
cd nome-do-projeto
composer install
```

## Configuração

Copie o arquivo de ambiente e ajuste os valores da aplicação e do banco de dados:

```bash
cp .env.example .env
```

Principais variáveis disponíveis:

```dotenv
APP_ENV=development
APP_NAME="Minha aplicação"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web
DB_USERNAME=root
DB_PASSWORD=
```

## Executando a aplicação

Inicie o servidor de desenvolvimento apontando o document root para a pasta `public`:

```bash
php -S localhost:8000 -t public
```

A aplicação estará disponível em <http://localhost:8000>.

## Hospedagem em um servidor web

Para publicar a aplicação em uma hospedagem ou servidor de produção, consulte a documentação oficial do Slim Framework 4 sobre servidores web:

<https://www.slimframework.com/docs/v4/start/web-servers.html>

As configurações corretas dependem do servidor utilizado, como Apache, Nginx ou PHP-FPM. Em geral, o document root deve apontar para a pasta `public`, que contém o arquivo `index.php` da aplicação. Também é necessário configurar o servidor para encaminhar as requisições para esse arquivo e garantir que o PHP e as extensões exigidas estejam habilitados.

Antes da publicação, revise também as variáveis do `.env`, principalmente `APP_ENV`, as credenciais do banco de dados e as permissões das pastas usadas pelo Twig.

## Estrutura principal

```text
config/                 Configurações do banco e do Twig
public/index.php        Ponto de entrada da aplicação
src/Controllers/        Controllers
src/Routes/             Rotas da aplicação
src/Views/              Templates Twig
.env.example             Exemplo de variáveis de ambiente
```

## Criando um novo projeto

Depois da instalação, use esta estrutura como base e adapte-a ao domínio da aplicação:

1. Crie as rotas em `src/Routes`.
2. Implemente os controllers em `src/Controllers`.
3. Adicione os templates em `src/Views`.
4. Crie os models usando o Eloquent conforme a necessidade do projeto.
5. Mantenha as credenciais e configurações específicas no arquivo `.env`.

## Licença

Este projeto está disponível sob a licença MIT.
