<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Allows to external tool to ping your instalation and know if the site is up.
Route::get('/status', function() {
	return "ALIVE";
});


// Gate controllers
$controllers = [
    'GitHub'
];
foreach ($controllers as $controller) {
    Route::get( '/gate/' . $controller, "Gate\\${controller}GateController@onGet");
    Route::post('/gate/' . $controller, "Gate\\${controller}GateController@onPost");
}
