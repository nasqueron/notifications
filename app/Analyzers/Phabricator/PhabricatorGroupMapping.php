<?php

namespace Nasqueron\Notifications\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\ItemGroupMapping;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

class PhabricatorGroupMapping extends ItemGroupMapping {
    
    ///
    /// Extra properties
    ///

    /**
     * An array of words, each item a string with a word to find in the story.
     *
     * @var array
     */
    public $words = [];

    ///
    /// Helper methods to process words
    ///

    /**
     * Determines if the specified story belong to this mapping
     */
    public function doesStoryBelong (PhabricatorStory $story) : bool {
        foreach ($this->words as $word) {
            if (stripos($story->text, $word) !== false) {
                return true;
            }
        }
        return false;
    }
    
}
