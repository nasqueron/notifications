<?php

namespace Nasqueron\Notifications\Config\Reporting;

final class FeatureReportEntry extends BaseReportEntry {

    ///
    /// Public properties
    ///

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $enabled;

    ///
    /// Constructor
    ///

    /**
     * Initializes a new instance of the FeatureReportEntry class.
     *
     * @var name The feature name
     * @var bool If the feature enabled, true. Otherwise, false.
     */
    public function __construct (string $name, bool $enabled) {
        $this->name = $name;
        $this->enabled = $enabled;
    }

    ///
    /// Format
    ///

    /**
     * Gets the entry as an array.
     *
     * @return string[]
     */
    public function toArray () : array {
        return [
            $this->name,
            (string)$this->enabled,
        ];
    }

    /**
     * Gets the entry as an array. Formats empty string.
     *
     * @return string[]
     */
    public function toFancyArray () : array {
        return [
            $this->name,
            self::fancyBool($this->enabled, '✓'),
        ];
    }

}
