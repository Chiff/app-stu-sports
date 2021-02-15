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
    return $router->app->version();
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('events',  ['uses' => 'EventsController@showUserEvents']);

    $router->get('events/{id}', ['uses' => 'EventsController@showOneEvent']);

    $router->post('events', ['uses' => 'EventsController@create']);

    $router->delete('events/{id}', ['uses' => 'EventsController@delete']);

    $router->put('events/{id}', ['uses' => 'EventsController@update']);
});
