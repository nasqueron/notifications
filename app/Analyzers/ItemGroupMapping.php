<?php

namespace Nasqueron\Notifications\Analyzers;

/**
 * Map items (repositories, projects, items, etc.) names to groups
 */
class ItemGroupMapping {

    ///
    /// Properties
    ///

    /**
     * The group the mapped items belong to
     *
     * @var string
     */
    public $group;

    /**
     * An array of the items to map, each item a string with the name of the
     * repository, project or item used for mapping.
     * The wildcard '*' is allowed to specify several items.
     *
     * @var array
     */
    public $items = [];

    ///
    /// Helper methods
    ///

    /**
     * Determines if the specified item matches a pattern.
     *
     * @param string $pattern The pattern, with * allowed as wildcard character
     * @param string $item The item name to compare with the pattern
     * @return bool
     */
    public static function doesItemMatch (
        string $pattern,
        string $item
    ) : bool {
        return str_is($pattern, $item);
    }

    /**
     * Determines if the specified item belong to this mapping
     *
     * @return bool
     */
    public function doesItemBelong (string $actualItem) : bool {
        foreach ($this->items as $candidateItem) {
            if (static::doesItemMatch($candidateItem, $actualItem)) {
                return true;
            }
        }

        return false;
    }

}
