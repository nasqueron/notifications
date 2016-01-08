<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nasqueron\Notifications\Services\Services
 */
class Services extends Facade {

    /**
     * Gets the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'services';
    }

}
