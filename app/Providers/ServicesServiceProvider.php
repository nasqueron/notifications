<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Config\Services\Services;

class ServicesServiceProvider extends ServiceProvider {
    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('services', function (Application $app) {
            $path = config('services.gate.credentials');
            if (strlen($path) > 0 && $app->make('filesystem')->has($path)) {
                return Services::loadFromJson($path);
            }

            return new Services;
        });
    }
}
