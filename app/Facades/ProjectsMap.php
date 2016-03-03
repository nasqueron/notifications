<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory
 */
class ProjectsMap extends Facade {

    /**
     * Gets the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'phabricator-projectsmap';
    }

}
