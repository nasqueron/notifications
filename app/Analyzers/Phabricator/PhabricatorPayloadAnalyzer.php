<?php

namespace Nasqueron\Notifications\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\BasePayloadAnalyzer;

use Nasqueron\Notifications\Phabricator\PhabricatorStory;

class PhabricatorPayloadAnalyzer extends BasePayloadAnalyzer {

    /**
     * The name of the service, used to get specific classes and config
     */
    const SERVICE_NAME = "Phabricator";

    ///
    /// Private members
    ///

    /**
     * The story
     * @var PhabricatorStory
     */
    private $story;

    ///
    /// Constructor
    ///

    /**
     * Creates a new PhabricatorPayloadAnalyzer instance.
     *
     * @param string $project
     * @param PhabricatorStory $story
     */
    public function __construct($project, PhabricatorStory $story) {
        $this->project = $project;
        $this->story = $story;

        $this->loadConfiguration();
    }

    ///
    /// Qualification of the story
    ///

    /**
     * Gets the group for a specific story.
     *
     * @return string the group, central part of the routing key
     */
    public function getGroup () {
        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        foreach ($this->configuration->map as $mapping) {
            foreach ($this->story->getProjects() as $project) {
                if ($mapping->doesItemBelong($project)) {
                    return $mapping->group;
                }
            }
        }

        // Words
        foreach ($this->configuration->map as $mapping) {
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
