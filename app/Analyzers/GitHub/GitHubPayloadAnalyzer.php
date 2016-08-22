<?php

namespace Nasqueron\Notifications\Analyzers\GitHub;

use Nasqueron\Notifications\Analyzers\GitHub\Events\Event;
use Nasqueron\Notifications\Analyzers\GitHub\Events\UnknownEvent;

use Config;
use Storage;

class GitHubPayloadAnalyzer {

    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    private $project;

    /**
     * The GitHub event triggering this request
     * @var string
     */
    private $event;

    /**
     * The request content, as a structured data
     * @var stdClass
     */
    private $payload;

    /**
     * The configuration for the payload analyzer
     * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzerConfiguration;
     */
    private $configuration;

    /**
     * The payload analyzer event
     *
     * @var Nasqueron\Notifications\Analyzers\GitHub\Events\Event;
     */
    private $analyzerEvent;

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
    public function __construct($project, $event, $payload) {
        if (!is_object($payload)) {
            throw new \InvalidArgumentException("Payload must be an object.");
        }

        $this->project = $project;
        $this->event = $event;
        $this->payload = $payload;

        $this->loadConfiguration($project);

        try {
            $this->analyzerEvent = Event::forPayload($event, $payload);
        } catch (\InvalidArgumentException $ex) {
            $this->analyzerEvent = new UnknownEvent($event);
        }
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
        $dir = Config::get('services.github.analyzer.configDir');
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
            new GitHubPayloadAnalyzerConfiguration($this->project)
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
    public function getRepository () {
        if ($this->isAdministrativeEvent()) {
            return '';
        }

        return $this->payload->repository->name;
    }

    ///
    /// Qualification of the payload
    ///

    /**
     * @return bool
     */
    public function isAdministrativeEvent () {
        $administrativeEvents = [
            'membership',    // Member added to team
            'ping',          // Special ping pong event, fired on new hook
            'repository',    // Repository created
        ];

        return in_array($this->event, $administrativeEvents);
    }

    /**
     * Gets the group for a specific payload.
     *
     * @return string The group, central part of the routing key
     */
    public function getGroup () {
        // Some events are organization-level only and can't be mapped to an
        // existing repository.
        if ($this->isAdministrativeEvent()) {
            return $this->configuration->administrativeGroup;
        }

        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        $repository = $this->getRepository();
        foreach ($this->configuration->repositoryMapping as $mapping) {
            if ($mapping->doesItemBelong($repository)) {
                return $mapping->group;
            }
        }

        return $this->configuration->getDefaultGroup();
    }

    ///
    /// Description of the payload
    ///

    /**
     * Gets a short textual description of the event.
     *
     * @return string
     */
    public function getDescription () {
        return $this->analyzerEvent->getDescription();
    }

    /**
     * Gets a link to view the event on GitHub.
     *
     * @return string The most relevant URL
     */
    public function getLink () {
      return $this->analyzerEvent->getLink();
    }

}
