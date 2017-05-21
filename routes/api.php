<?php

// use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


$api = app('Dingo\Api\Routing\Router');

$api->group(['version' => 'v1', 'namespace' => 'App\Http\Controllers', 'middleware' => 'header'], function($api) {
	$api->post('/login', 'AuthController@postLogin');

	$api->group(['middleware' => 'jwt.auth'], function($api) {
		$api->resource('/customers', 'CustomersController');
		$api->get('/customers/{id}/edit', 'CustomersController@edit');
		$api->get('/auth-users', 'UsersController@authUser');
		$api->resource('/users', 'UsersController');
		$api->resource('/locations', 'LocationsController');
		$api->resource('/stations', 'StationsController');
		$api->get('/stations/{id}/edit', 'StationsController@edit');
		$api->resource('/games', 'GamesController');
		$api->get('/games/{id}/edit', 'GamesController@edit');
		$api->resource('/companies', 'CompaniesController');
		$api->resource('/credits', 'CreditsController');
		$api->resource('/sessions', 'SessionsController');
		$api->resource('/commands', 'CommandsController');
		$api->get('/stations/{stationId}/commands', 'CommandsController@showByStation');
		$api->resource('/addresses', 'AddressesController');
		$api->resource('/pings', 'PingsController');
	});
	
	$api->post('/customers/vend/{token?}', 'CustomersController@vend');
	$api->post('/credits/vend/{token?}', 'CreditsController@vend');
});