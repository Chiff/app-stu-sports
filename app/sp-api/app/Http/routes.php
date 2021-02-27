<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return "hello stranger";
});

$router->group(['prefix' => 'api', 'middleware' => ['auth']], function () use ($router) {

    $router->group(['prefix' => 'event'], function () use ($router) {
        $router->get('', ['uses' => 'EventsController@showUserEvents']);

        $router->get('/{id}', ['uses' => 'EventsController@showOneEvent']);

        $router->post('', ['uses' => 'EventsController@create']);

        $router->delete('/{id}', ['uses' => 'EventsController@delete']);

        $router->put('/{id}', ['uses' => 'EventsController@update']);
    });

});
