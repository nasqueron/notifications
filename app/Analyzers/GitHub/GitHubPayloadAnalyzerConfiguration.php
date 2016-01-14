<?php

namespace Nasqueron\Notifications\Analyzers\GitHub;

class GitHubPayloadAnalyzerConfiguration {
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
     * @var RepositoryGroupMapping[]
     */
    public $repositoryMapping;
}
