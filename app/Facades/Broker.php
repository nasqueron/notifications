<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Keruald\Broker\Broker
 */
class Broker extends Facade {

    /**
     * Gets the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string {
        return 'broker';
    }

}
