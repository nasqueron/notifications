<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SentryServiceProvider extends ServiceProvider
{
    /**
     * Bootstraps the application services.
     *
     * @return void
     */
    public function boot() {
    }

    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('raven', function (Application $app) {
            $config = $app->make('config');
            $dsn = $config->get('services.sentry.dsn');
            return new \Raven_Client($dsn);
        });
    }
}
