<?php

namespace Nasqueron\Notifications\Analyzers;

class PhabricatorPayloadAnalyzerConfiguration {
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
     * @var PhabricatorGroupMapping[]
     */
    public $groupsMapping;
}
