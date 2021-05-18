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
        $router->get('', ['uses' => 'EventsController@showAllEvents']);
        $router->get('/my', ['uses' => 'EventsController@showUserEvents']);
        $router->get('/byid/{id}', ['uses' => 'EventsController@showOneEventById']);
        $router->get('/byid/{id}/guest', ['uses' => 'EventsController@showOneEventByIdUnsecured']);
        $router->get('/byname/{name}', ['uses' => 'EventsController@showOneEventsByEventName']);
        $router->post('', ['uses' => 'EventsController@createOneEvent']);
        $router->delete('/{id}', ['uses' => 'EventsController@deleteOneEvent']);
        $router->put('/{id}', ['uses' => 'EventsController@update']);
        $router->post('/{id}/finish', ['uses' => 'EventsController@finishEventById']);
        $router->post('/{id}/points', ['uses' => 'EventsController@addPointsById']);
        $router->post('/create', ['uses' => 'EventsController@createOneEvent']);
        $router->post('/addTeamById', ['uses' => 'EventsController@signTeamById']);
        $router->post('/addParticipantByMail', ['uses' => 'EventsController@addOneParticipantToEventByEmail']);
        $router->get('teams/{id}', ['uses' => 'EventsController@showTeamsOnEvent']);
        $router->delete('{event_id}/teams/delete/{team_id}', ['uses' => 'EventsController@deleteTeamByIdFromEvent']);
        $router->put('/disable/{event_id}', ['uses' => 'EventsController@disableEventById']);
        $router->post('/runtask/{stringId}', ['uses' => 'EventsController@runTask']);
    });

    $router->group(['prefix' => 'team'], function () use ($router) {
        $router->get('', ['uses' => 'TeamController@showAllTeams']);
        $router->get('my', ['uses' => 'TeamController@showAllUserTeams']);
        $router->get('{id}', ['uses' => 'TeamController@getTeamById']);
//        $router->get('my/15', ['uses' => 'TeamController@getTeamById']);
        $router->post('create', ['uses' => 'TeamController@createTeam']);
        $router->put('update', ['uses' => 'TeamController@updateTeam']);
        $router->post('{id}/add', ['uses' => 'TeamController@addOneMemberToTeamByEmail']);
        $router->delete('/delete/{id}', ['uses' => 'TeamController@deleteTeamById']);

    });

    $router->group(['prefix' => 'ciselnik'], function () use ($router) {
        $router->get('{type}', ['uses' => 'CiselnikController@getType']);
    });

    $router->group(['prefix' => 'system'], function () use ($router) {
        $router->get('/tasks', ['uses' => 'SystemController@getActiveTasks']);
        $router->post('/runtask/{stringId}', ['uses' => 'SystemController@runTask']);
    });

});
