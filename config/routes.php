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

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/api/v1', function () {
    Router::addGroup('/clients', function () {
        Router::post('', [Controller\ClientController::class, 'create']);
        Router::get('/{id}', [Controller\ClientController::class, 'get']);
        Router::put('/{id}', [Controller\ClientController::class, 'update']);
        Router::delete('/{id}', [Controller\ClientController::class, 'delete']);

        Router::post('/{id}/favorites', [Controller\ClientController::class, 'addFavoriteProduct']);
    });
});
