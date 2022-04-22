<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'Api',
], function() {

	Route::resource('gps-status','GpsStatusController')->only(['store']);
	Route::post('gps-status/teltonika','GpsStatusController@teltonika');
	Route::post('gps/engineoffon','GpsStatusController@engineOffOn');
	Route::get('gps/currentstatus','GpsStatusController@currentStatus');
	Route::resource('document','DocumentController')->only(['store']);
	Route::resource('crossdocking','CrossDockingController')->only(['store']);
});

Route::group(['namespace' => 'Api\Mobile', 'prefix' => 'app', 'middleware' => 'log.api'], function() {

	Route::get('test/{id}','MainAppController@test');
	Route::post('check','MainAppController@check');
	Route::post('checkin','MainAppController@checkIn');
	Route::post('location','CellphoneStatusController@getLocation');
	Route::post('updatedriver','MainAppController@updateDriver');
	Route::post('updatevehicle','MainAppController@updateVehicle');
	Route::post('getdb','MainAppController@getDB');
	Route::post('getoperation','MainAppController@getOperation');
	Route::post('routeinfo','MainAppController@routeInfo');
	Route::post('tripinfo','MainAppController@tripInfo');
	Route::post('incidenceinfo','MainAppController@IncidenceInfo');
	//Route::post('getapp','MainAppController@getAppUpdate');

});