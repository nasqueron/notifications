<?php

namespace Nasqueron\Notifications\Analyzers;

use Config;
use Storage;

use BadMethodCallException;
use InvalidArgumentException;

abstract class BasePayloadAnalyzer {

    ///
    /// Constants
    ///

    /**
     * The name of the service, used to get specific classes and config
     */
    const SERVICE_NAME = "UnknownService";

    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    protected $project;

    /**
     * The request content, as a structured data
     * @var \stdClass
     */
    protected $payload;

    /**
     * The configuration for the payload analyzer
     * @var PayloadAnalyzerConfiguration;
     */
    protected $configuration;

    ///
    /// Constructor
    ///

    /**
     * Creates a new JenkinsPayloadAnalyzer instance.
     *
     * @param string $project
     * @param  \stdClass $payload
     */
    public function __construct(string $project, \stdClass $payload) {
        $this->project = $project;
        $this->payload = $payload;

        $this->loadConfiguration();
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
    public function getConfigurationFileName () : string {
        $dir = Config::get('services.' . strtolower(static::SERVICE_NAME) . '.analyzer.configDir');

        $filename = $dir . '/' . $this->project . '.json';

        if (!Storage::has($filename)) {
            return $dir . '/' . static::CONFIG_DEFAULT_FILE;
        }

        return $filename;
    }

    /**
     * Gets full qualified class name for configuration.
     *
     * @return string
     */
    private function getCandidateConfigurationClassName() : string {
        $namespace = 'Nasqueron\Notifications\Analyzers\\' . static::SERVICE_NAME;
        return $namespace . "\\" . static::SERVICE_NAME . 'PayloadAnalyzerConfiguration';
    }

    /**
     * Gets full qualified class name for configuration if existing,
     * or PayloadAnalyzerConfiguration class if not.
     *
     * @return string The configuration class to use
     */
    private function getConfigurationClassName () : string {
        $class = $this->getCandidateConfigurationClassName();

        if (class_exists($class)) {
            return $class;
        }

        return PayloadAnalyzerConfiguration::class;
    }

    /**
     * Loads configuration for the analyzer
     */
    public function loadConfiguration () : void {
        $fileName = $this->getConfigurationFileName();
        $class = $this->getConfigurationClassName();

        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(Storage::get($fileName)),
            new $class($this->project)
        );
    }

    ///
    /// Properties
    ///

    /**
     * Gets the name of the item.
     *
     * @var string
     */
    public function getItemName () : string {
        throw new BadMethodCallException("The getItemName method must be implemented in the analyzer class if used.");
    }

    /**
     * Determines if the event isn't related to a specific item,
     * but to the general service.
     *
     * @return bool
     */
    public function isAdministrativeEvent () : bool {
        return false;
    }

    /**
     * Gets the group for a specific payload.
     *
     * @return string The group, central part of the routing key
     */
    public function getGroup () : string {
        // Some events are organization-level only and can't be mapped
        // to projects.
        if ($this->isAdministrativeEvent()) {
            return $this->configuration->administrativeGroup;
        }

        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        $item = $this->getItemName();
        foreach ($this->configuration->map as $mapping) {
            if ($mapping->doesItemBelong($item)) {
                return $mapping->group;
            }
        }

        return $this->configuration->getDefaultGroup();
    }

}
