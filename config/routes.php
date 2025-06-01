<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;
use App\Controller;
use App\Middleware;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/api/v1', function () {
    Router::post('/auth', [Controller\UserController::class, 'auth']);

    Router::addGroup('/clients', function () {
        /**
         * Client routes
         */
        Router::post('', [Controller\ClientController::class, 'create'], [
            'middleware' => [App\Middleware\BodyValidationMiddleware::class],
            'body_rules' => ['name' => 'required|string', 'email' => 'required|email']
        ]);
        Router::get('/{id}', [Controller\ClientController::class, 'get'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required']
        ]);
        Router::put('/{id}', [Controller\ClientController::class, 'update'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class,  App\Middleware\BodyValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required'],
            'body_rules' => ['name' => 'string|nullable', 'email' => 'email|nullable']
        ]);
        Router::delete('/{id}', [Controller\ClientController::class, 'delete'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required']
        ]);
        Router::get('', [Controller\ClientController::class, 'list'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class],
            'url_rules' => ['limit' => 'integer|nullable']
        ]);
        /**
         * Client favorite products routes
         */
        Router::post('/{id}/favorites', [Controller\ClientFavoriteProductController::class, 'addFavorite'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class,  App\Middleware\BodyValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required'],
            'body_rules' => ['product_id' => 'integer|required']
        ]);
        Router::post('/{id}/favorites/batch', [Controller\ClientFavoriteProductController::class, 'AddManyFavorites'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class,  App\Middleware\BodyValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required'],
            'body_rules' => ['product_ids' => 'array|required']
        ]);
        Router::get('/{id}/favorites', [Controller\ClientFavoriteProductController::class, 'listFavorites'], [
            'middleware' => [Middleware\UrlParamsValidationMiddleware::class],
            'url_rules' => ['id' => 'uuid|required', 'limit' => 'integer|nullable']
        ]);
    }, ['middleware' => [Middleware\AuthMiddleware::class]  ]);
});
