<?php

namespace Nasqueron\Notifications\Analyzers\GitHub;

class GitHubPayloadAnalyzerConfiguration {

    ///
    /// Private members
    ///

    /**
     * The project this configuration is for
     *
     * @var string
     */
    private $project;

    ///
    /// Public properties
    ///

    /**
     * The group for organization only events
     *
     * @var string
     */
    public $administrativeGroup;

    /**
     * The default group to fallback for any event not mapped in another group
     *
     * @var string
     */
    public $defaultGroup;

    /**
     * An array of RepositoryGroupMapping objects to match repositories & groups
     *
     * @var RepositoryGroupMapping[]
     */
    public $repositoryMapping;

    ///
    /// Constructor
    ///

    /**
     * Initializes a new instance of the GitHubPayloadAnalyzerConfiguration class
     *
     * @param string $project The project name this configuration is related to
     */
    public function __construct ($project) {
        $this->project = $project;
    }

    ///
    /// Helper methods
    ///

    /**
     * Gets the default group
     *
     * @return string the default group, as set in the configuration,
     *                or if omitted, the project name as fallback.
     */
    public function getDefaultGroup () {
        if (empty($this->defaultGroup)) {
            return strtolower($this->project);
        }

        return $this->defaultGroup;
    }
}
