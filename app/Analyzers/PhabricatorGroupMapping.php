<?php

namespace Nasqueron\Notifications\Analyzers;

use Nasqueron\Notifications\Phabricator\PhabricatorStory;

class PhabricatorGroupMapping {
    ///
    /// Properties
    ///

    /**
     * The group the mapped projects belong to
     *
     * @var string
     */
    public $group;

    /**
     * An array of the projects, each item a string with the name of the
     * project. The wildcard '*' is allowed to specify several projects.
     *
     * @var array
     */
    public $projects;

    /**
     * An array of words, each item a string with a word to find in the story.
     *
     * @var array
     */
    public $words = [];

    ///
    /// Helper methods
    ///

    /**
     * Determines if the specified project matches a pattern
     *
     * @param string $pattern The pattern, with * allowed as wildcard character
     * @param string $project The project name to compare with the pattern
     * @return bool
     */
    public static function doesProjectMatch ($pattern, $project) {
        return str_is($pattern, $project);
    }

    /**
     * Determines if the specified project belong to this mapping
     *
     * @return bool
     */
    public function doesProjectBelong ($actualProject) {
        foreach ($this->projects as $candidateProject) {
            if (static::doesProjectMatch($candidateProject, $actualProject)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determines if the specified story belong to this mapping
     *
     * @return bool
     */
    public function doesStoryBelong (PhabricatorStory $story) {
        foreach ($this->words as $word) {
            if (strpos($story->text, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
