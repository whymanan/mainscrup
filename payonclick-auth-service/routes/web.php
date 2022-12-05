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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
      // Matches "/api/login
     $router->post('login', 'AuthController@login');
     $router->get('users', 'UserController@allUsers');

});

// Aeps group

$router->group(['prefix' => 'api'], function () use ($router) {
    
    $router->get('aepsbanks', 'AepsController@aepsbanks');
    $router->post('aepstransection', 'AepsController@aepsTransaction');

});

// Dmt group

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('dmttransaction', 'DmtController@dmtTransaction');

});
// CIB group

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('cibregistration', 'CIBController@CibRegistration');

});


// Sms group

$router->group(['prefix' => 'api'], function () use ($router) {
  $router->post('textsms', ['uses' => 'SmsController@textsms']);
});
