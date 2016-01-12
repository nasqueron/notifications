<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Keruald\Broker\BrokerFactory;

class BrokerServiceProvider extends ServiceProvider {

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
        $this->app->singleton('broker', function (Application $app) {
            $config = $app->make('config');
            $driver = $config->get('broker.driver');
            $params = $config->get('broker.connections.' . $driver);

            return BrokerFactory::make($params);
        });
    }
}
