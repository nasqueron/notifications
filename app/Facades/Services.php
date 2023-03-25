<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nasqueron\Notifications\Config\Services\Services
 */
class Services extends Facade {

    /**
     * Gets the registered name of the component.
     */
    protected static function getFacadeAccessor() : string {
        return 'services';
    }

}
