<?php

namespace Nasqueron\Notifications\Config\Reporting;

class FeatureReportEntry {

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

}
