<?php

namespace Nasqueron\Notifications\Phabricator;

class ProjectsMapFactory {

    /**
     * Loads projects map from cache or fetches it from API if not cached.
     *
     * @param string $instanceName The Phabricator instance name
     * @return Nasqueron\Notifications\Phabricator\ProjectsMap
     */
    public function load ($instanceName) {
        return ProjectsMap::load($instanceName);
    }

    /**
     * Fetches projects map from API.
     *
     * @param string $instanceName The Phabricator instance name
     * @return Nasqueron\Notifications\Phabricator\ProjectsMap
     */
    public function fetch ($instanceName) {
        return ProjectsMap::fetch($instanceName);
    }

}
