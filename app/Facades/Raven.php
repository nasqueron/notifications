<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

use Config;

/**
 * @see \Raven_Client
 */
class Raven extends Facade {

    /**
     * Gets the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'raven';
    }

    /**
     * Determines if a Sentry DSN is provided in the configuration
     */
    public static function isConfigured () {
        return Config::get('services.sentry.dsn') !== null;
    }
}
