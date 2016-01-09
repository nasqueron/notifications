<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Services\Services;

class ServicesServiceProvider extends ServiceProvider {
    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('services', function (Application $app) {
            $path = config('services.gate.credentials');
            if ($app->make('filesystem')->has($path)) {
                return Services::loadFromJson($path);
            }

            return new Services;
        });
    }
}
