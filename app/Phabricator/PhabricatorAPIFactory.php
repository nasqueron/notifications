<?php

namespace Nasqueron\Notifications\Phabricator;

use Nasqueron\Notifications\Contracts\APIFactory;

class PhabricatorAPIFactory implements APIFactory {

    /**
     * Gets an instance of the Phabricator API client class.
     *
     * @param string $instance The Phabricator instance
     * @return \Nasqueron\Notifications\Phabricator\PhabricatorAPI
     */
    public function get (string $instance) {
        return PhabricatorAPI::forInstance($instance);
    }

    /**
     * Gets an instance of the Phabricator API client class for a project.
     *
     * @param string $project The Phabricator project name
     * @return PhabricatorAPI
     */
    public function getForProject (string $project) {
        return PhabricatorAPI::forProject($project);
    }
}
