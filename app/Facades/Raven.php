<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Raven_Client
 */
class Raven extends Facade {

    /**
     * Gets the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string {
        return 'raven';
    }

    /**
     * Determines if a Sentry DSN is provided in the configuration
     */
    public static function isConfigured () : bool {
        return Config::get('services.sentry.dsn') !== null;
    }
}
