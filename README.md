<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Dependências
- [Composer ~v.1.9](https://getcomposer.org/)
- [NPM ~v6.14](https://www.npmjs.com/) ou Yarn
- [Node ~v10.15](https://nodejs.org/en/)
- PHP ^7.2
- [Apache2 ~v2.4](https://www.apache.org/)

### Parte I - Instalação do sistema

1. Tenha certeza que as dependências estão instaladas e atualizadas.

2. Vá ao diretório em questão e clone o repositório:
   `cd /var/www/html`
   `git clone https://github.com/wlademirdumaresq/inovall.git`

3. Entre na raiz do projeto e instale o composer e o npm:
   `cd inovall`
   `git pull origin main`
   `composer install && npm install`
   `npm run dev`

4. Ainda na raiz, crie um arquivo .env:
   `cp .env.example .env`

5. Ainda na raiz, gere a chave:
   `php artisan key:generate`

6. Rode o comando de migração :
   `php artisan migrate`

7. Configure o .env e rode:
   A configuração .env envolve as variáveis APP_ (opcional) e DB_ (obrigatórias).
   `php artisan config:cache`

### Parte II - Acesso do sistema

1. Rode o comando serve.
   `php artisan serve`
2. O comando lhe dará um link de acesso.
   
3. Será redirecionado para pagina de login onde poderá fazer login da seguintes formas:
   `usuario: CPF (11111111111) || EMAIL (admin@admin.com)|| USERNAME(admin)`
   `password: admin`
