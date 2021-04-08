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

//$router->get('/', function () use ($router) {
//    return "hello stranger";
//});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('login', ['uses' => 'UserController@login']);
        $router->get('logout', ['uses' => 'UserController@logout']);
        $router->get('detail', ['uses' => 'UserController@detail']);
        $router->get('all', ['uses' => 'UserController@showAllUsers']);
    });

    $router->group(['prefix' => 'event'], function () use ($router) {

        $router->get('', ['uses' => 'EventsController@showUserEvents']);
        $router->get('/{id}', ['uses' => 'EventsController@showOneEvent']);
        $router->post('', ['uses' => 'EventsController@createOneEvent']);
        $router->delete('/{id}', ['uses' => 'EventsController@delete']);
        $router->put('/{id}', ['uses' => 'EventsController@update']);
        $router->post('/create', ['uses' => 'EventsController@create']);

    });

});
