<?php

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

$router->group([
    'prefix' => 'api/v1',
], function () use ($router) {
    //root
    $router->get('/', 'ExampleController@index');

    //User authentication
    $router->post('/login','UsersController@authenticate');

    //users Response
    $router->post('/users', 'UsersController@create');
    $router->get('/users', 'UsersController@index');

});
