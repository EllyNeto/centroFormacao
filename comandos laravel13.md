# Comandos Laravel 13

## ⚠️ Instalação das extensões PHP (apenas UMA VEZ)

Não precisa repetir isto sempre. Depois de instaladas, não é preciso instalar novamente.

```sh
sudo apt-get install php8.5-xml php8.5-curl php8.5-mbstring php8.5-sqlite3
```

Sem estas extensões (especialmente `ext-dom`/`ext-xml` para a classe `DOMDocument`), nenhum comando `php artisan` funciona (falha com `Class "DOMDocument" not found`).

## Verificar versões

```sh
php -v
composer -V
```

## Instalar dependências do Composer

```sh
composer install --no-interaction --prefer-dist
```

Se faltarem extensões PHP, adicione `--ignore-platform-reqs`:

```sh
composer install --no-interaction --prefer-dist --ignore-platform-reqs
```

## Versão do Laravel

```sh
php artisan --version
```

## Gerar a chave da aplicação (APP_KEY)

```sh
php artisan key:generate
```

## Instalar o Laravel Boost

```sh
composer require laravel/boost --dev --no-interaction
php artisan boost:install
```

## Servir a aplicação

```sh
php artisan serve
```

## Comandos úteis

```sh
php artisan list
php artisan route:list
php artisan config:show app.name
composer require laravel/boost --dev
```

## Estado atual do projeto

- PHP 8.5.12 e Composer 2.11.1 instalados
- `vendor/autoload.php` presente (dependências instaladas)
- Laravel Framework 13.40.0
- `APP_KEY` definido em `.env` (gerado manualmente)
- Pacote `laravel/boost` instalado em `require-dev`
- Nenhum commit efetuado
