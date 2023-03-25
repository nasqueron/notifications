<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory
 */
class PhabricatorAPI extends Facade {

    /**
     * Gets the registered name of the component.
     */
    protected static function getFacadeAccessor() : string {
        return 'phabricator-api';
    }

}
