<?php

namespace Nasqueron\Notifications\Phabricator;

class ProjectsMapFactory {

    /**
     * Loads projects map from cache or fetches it from API if not cached
     *
     * @param string $instance The Phabricator instance
     * @return Nasqueron\Notifications\Phabricator\ProjectsMap
     */
    public function load ($instance) {
        return ProjectsMap::load($instance);
    }

    /**
     * Fetches projects map from API
     *
     * @param string $instance The Phabricator instance
     * @return Nasqueron\Notifications\Phabricator\ProjectsMap
     */
    public function fetch ($instance) {
        return ProjectsMap::fetch($instance);
    }

}
