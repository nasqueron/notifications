<?php

namespace Nasqueron\Notifications\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\PayloadAnalyzerConfiguration;

class PhabricatorPayloadAnalyzerConfiguration extends PayloadAnalyzerConfiguration {

    /**
     * An array of RepositoryGroupMapping objects to match repositories & groups
     *
     * @var PhabricatorGroupMapping[]
     */
    public $map;

}
