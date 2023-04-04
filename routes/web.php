<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version() . ' wallet app';
});

$router->group(['prefix' => '/api/v1/'], function () use ($router) {

    $router->get('get-balance', [
        'as' => 'get.balance',
        'uses' => '\App\Http\Controllers\DepositController@balance',
    ]);

    $router->post('add-money', [
        'as' => 'add.money',
        'uses' => 'DepositController@addMoney',
    ]);
});
