<?php

use Nasqueron\Notifications\Features;

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
if (Features::isEnabled('Gate')) {
    foreach (Config::get('gate.controllers') as $controller) {
        $controllerRoute = '/gate/' . $controller . '/';
        Route::get($controllerRoute . '{door?}', "Gate\\${controller}GateController@onGet");
        Route::post($controllerRoute . '{door}', "Gate\\${controller}GateController@onPost");
    }
}
