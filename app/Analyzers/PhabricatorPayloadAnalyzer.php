<?php

namespace Nasqueron\Notifications\Analyzers;

use Nasqueron\Notifications\Phabricator\PhabricatorStory;

use Config;
use Storage;

class PhabricatorPayloadAnalyzer {
    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    private $project;

    /**
     * The story
     * @var PhabricatorStory
     */
    private $story;

    /**
     * The configuration for the payload analyzer
     * @var PhabricatorPayloadAnalyzerConfiguration;
     */
    private $configuration;

    ///
    /// Constructor
    ///

    /**
     * Creates a new GitHubPayloadAnalyzer instance.
     *
     * @param string $project
     * @param string $event
     * @param stdClass $payload
     */
    public function __construct($project, PhabricatorStory $story) {
        $this->project = $project;
        $this->story = $story;

        $this->loadConfiguration($project);
    }

    ///
    /// Configuration
    ///

    const CONFIG_DEFAULT_FILE = 'default.json';

    public function getConfigurationFileName () {
        $dir = Config::get('services.phabricator.analyzer.configDir');
        $filename = $dir . '/' . $this->project . '.json';

        if (!Storage::has($filename)) {
            return $dir . '/' . static::CONFIG_DEFAULT_FILE;
        }

        return $filename;
    }

    public function loadConfiguration () {
        $fileName = $this->getConfigurationFileName();

        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(Storage::get($fileName)),
            new PhabricatorPayloadAnalyzerConfiguration()
        );
    }

    ///
    /// Qualification of the story
    ///

    public function isAdministrativeEvent () {
        //TODO: determine events who qualify as administrative
        return false;
    }

    /**
     * Gets the group for a specific story
     *
     * @return string the group, central part of the routing key
     */
    public function getGroup () {
        // Some events are organization-level only and can't be mapped
        // to projects.
        if ($this->isAdministrativeEvent()) {
            return $this->configuration->administrativeGroup;
        }

        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        foreach ($this->configuration->groupsMapping as $mapping) {
            foreach ($this->story->getProjects() as $project) {
                if ($mapping->doesProjectBelong($project)) {
                    return $mapping->group;
                }
            }
        }

        // Words
        foreach ($this->configuration->groupsMapping as $mapping) {
            if ($mapping->doesStoryBelong($this->story)) {
                return $mapping->group;
            }
        }

        // By default, fallback group is the project name or a specified value.
        if (empty($this->configuration->defaultGroup)) {
            return strtolower($this->project);
        }
        return $this->configuration->defaultGroup;
    }

}
