<?php

namespace Nasqueron\Notifications\Analyzers\Jenkins;

use Config;
use Storage;

use InvalidArgumentException;

class JenkinsPayloadAnalyzer {

    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    private $project;

    /**
     * The request content, as a structured data
     * @var stdClass
     */
    private $payload;

    /**
     * The configuration for the payload analyzer
     * @var Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzerConfiguration;
     */
    private $configuration;

    ///
    /// Constructor
    ///

    /**
     * Creates a new JenkinsPayloadAnalyzer instance.
     *
     * @param string $project
     * @param stdClass $payload
     */
    public function __construct($project, $payload) {
        if (!is_object($payload)) {
            throw new InvalidArgumentException("Payload must be an object.");
        }

        $this->project = $project;
        $this->payload = $payload;

        $this->loadConfiguration($project);
    }

    ///
    /// Configuration
    ///

    /**
     * The default name of the configuration file
     */
    const CONFIG_DEFAULT_FILE = 'default.json';

    /**
     * Gets the full path to the configuration file.
     *
     * @return string
     */
    public function getConfigurationFileName () {
        $dir = Config::get('services.jenkins.analyzer.configDir');
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
            new JenkinsPayloadAnalyzerConfiguration($this->project)
        );
    }

    ///
    /// Properties
    ///

    /**
     * Gets the name of the repository.
     *
     * @var string
     */
    public function getJobName () {
        return $this->payload->name;
    }

    /**
     * Gets the group for a specific payload.
     *
     * @return string The group, central part of the routing key
     */
    public function getGroup () {
        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        $item = $this->getJobName();
        foreach ($this->configuration->groupsMapping as $mapping) {
            if ($mapping->doesItemBelong($item)) {
                return $mapping->group;
            }
        }

        return $this->configuration->getDefaultGroup();
    }

}
