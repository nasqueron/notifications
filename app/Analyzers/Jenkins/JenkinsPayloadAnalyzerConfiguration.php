<?php

namespace Nasqueron\Notifications\Analyzers\Jenkins;

use Nasqueron\Notifications\Analyzers\PayloadAnalyzerConfiguration;

class JenkinsPayloadAnalyzerConfiguration extends PayloadAnalyzerConfiguration {

    ///
    /// Public properties
    ///

    /**
     * @var array
     */
    public $notifyOnlyOnFailure;

}
