<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Services\Services;

class ServicesServiceProvider extends ServiceProvider {
    /**
     * Registers the application services.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function register() {
        $this->app->singleton('services', function ($app) {
            $path = config('services.gate.credentials');
            return Services::loadFromJson($path);
        });
    }
}
