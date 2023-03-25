<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SentryServiceProvider extends ServiceProvider {

    /**
     * Bootstraps the application services.
     */
    public function boot() : void {
    }

    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('raven', function (Application $app) {
            $config = $app->make('config');
            $dsn = $config->get('services.sentry.dsn');
            return new \Raven_Client($dsn);
        });
    }
}
