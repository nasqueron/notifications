<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;
use Keruald\Broker\AMQPBroker as Broker;

use Config;

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
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function register() {
        $this->app->singleton('broker', function ($app) {
            $broker = new Broker();
            $broker->connect(
                Config::get('broker.connections.amqp.host'),
                Config::get('broker.connections.amqp.port'),
                Config::get('broker.connections.amqp.username'),
                Config::get('broker.connections.amqp.password'),
                Config::get('broker.connections.amqp.vhost')
            );
            return $broker;
        });
    }
}
