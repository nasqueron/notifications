<?php

namespace Nasqueron\Notifications\Analyzers;

class RepositoryGroupMapping {
    ///
    /// Properties
    ///

    /**
     * The group the mapped repositories belong to
     *
     * @var string
     */
    public $group;

    /**
     * An array of the repositories, each item a string with the name of the
     * repository. The wildcard '*' is allowed to specify several repositories.
     *
     * @var array
     */
    public $repositories;

    ///
    /// Helper methods
    ///

    /**
     * Determines if the specified repository matches a pattern
     *
     * @param string the pattern, with * and ? allowed
     * @param string the repository name to compare with the pattern
     * @return bool
     */
    public static function doesRepositoryMatch ($pattern, $repository) {
        return ($pattern == $repository) || fnmatch($pattern, $repository);
    }

    /**
     * Determines if the specified repository belong to this mapping
     *
     * @return bool
     */
    public function doesRepositoryBelong ($actualRepository) {
        foreach ($this->repositories as $candidateRepository) {
            if (static::doesRepositoryMatch($candidateRepository, $actualRepository)) {
                return true;
            }
        }
        return false;
    }
}
