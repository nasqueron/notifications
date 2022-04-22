<?php

namespace Nasqueron\Notifications\Phabricator;

class ProjectsMapFactory {

    /**
     * Loads projects map from cache or fetches it from API if not cached.
     *
     * @param string $instanceName The Phabricator instance name
     * @return ProjectsMap
     */
    public function load (string $instanceName) {
        return ProjectsMap::load($instanceName);
    }

    /**
     * Fetches projects map from API.
     *
     * @param string $instanceName The Phabricator instance name
     * @return ProjectsMap
     */
    public function fetch (string $instanceName) {
        return ProjectsMap::fetch($instanceName);
    }

}
