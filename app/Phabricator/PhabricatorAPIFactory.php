<?php

namespace Nasqueron\Notifications\Phabricator;

class PhabricatorAPIFactory {

    /**
     * Gets an instance of the Phabricator API client class
     *
     * @param string $instance The Phabricator instance
     * @return Nasqueron\Notifications\Phabricator\PhabricatorAPI
     */
    public function get ($instance) {
        return PhabricatorAPI::forInstance($instance);
    }

    /**
     * Gets an instance of the Phabricator API client class for a project
     *
     * @param string $project The Phabricator project name
     * @return Nasqueron\Notifications\Phabricator\PhabricatorAPI
     */
    public function getForProject ($project) {
        return PhabricatorAPI::forProject($project);
    }
}
