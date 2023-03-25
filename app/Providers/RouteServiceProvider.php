<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Nasqueron\Notifications\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot() : void {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router) : void {
        $router->group(['namespace' => $this->namespace], static function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
