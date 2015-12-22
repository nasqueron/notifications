<?php

namespace Nasqueron\Notifications\Analyzers;

use Nasqueron\Notifications\Phabricator\PhabricatorStory;

use Config;

class PhabricatorPayloadAnalyzer {
    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    private $project;

    /**
     * The story
     * @var PhabricatorStory
     */
    private $story;

    /**
     * The projects associated to this story
     * @var string[]
     */
    private $projects;

    /**
     * The configuration for the payload analyzer
     * @var PhabricatorPayloadAnalyzerConfiguration;
     */
    private $configuration;

    ///
    /// Constructor
    ///

    /**
     * Creates a new GitHubPayloadAnalyzer instance.
     *
     * @param string $project
     * @param string $event
     * @param stdClass $payload
     */
    public function __construct($project, PhabricatorStory $story, $projects) {
        $this->project = $project;
        $this->story = $story;
        $this->projects = $projects;

        $this->loadConfiguration($project);
    }

    ///
    /// Configuration
    ///

    const CONFIG_DEFAULT_FILE = 'default.json';

    public function getConfigurationFileName () {
        $dir = Config::get('services.phabricator.analyzer.configDir');
        $filename = $dir . '/' . $this->project . '.json';

        if (!file_exists($filename)) {
            return $dir . '/' . static::CONFIG_DEFAULT_FILE;
        }

        return $filename;
    }

    public function loadConfiguration () {
        $fileName = $this->getConfigurationFileName();

        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(file_get_contents($fileName)),
            new PhabricatorPayloadAnalyzerConfiguration()
        );
    }

    ///
    /// Qualification of the story
    ///

    public function isAdministrativeEvent () {
        //TODO: determine events who qualify as administrative
        return false;
    }

    /**
     * Gets the group for a specific story
     *
     * @return string the group, central part of the routing key
     */
    public function getGroup () {
        // Some events are organization-level only and can't be mapped
        // to projects.
        if ($this->isAdministrativeEvent()) {
            return $this->configuration->administrativeGroup;
        }

        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        foreach ($this->configuration->groupsMapping as $mapping) {
            foreach ($this->projects as $project) {
                if ($mapping->doesProjectBelong($project)) {
                    return $mapping->group;
                }
            }
        }

        // Words
        foreach ($this->configuration->groupsMapping as $mapping) {
            if ($mapping->doesStoryBelong($this->story)) {
                return $mapping->group;
            }
        }

        // By default, fallback group is the project name or a specified value.
        if (empty($this->configuration->defaultGroup)) {
            return strtolower($this->project);
        }
        return $this->configuration->defaultGroup;
    }

    ///
    /// Description of the payload
    ///

    /**
     * Gets repository and branch information
     *
     * @return string
     */
    public function getWhere () {
        $repo = $this->payload->repository->name;
        $branch = $this->payload->ref;
        return static::getRepositoryAndBranch($repo, $branch);
    }

    /**
     * Gets a repository and branch information string
     *
     * @param string $repo The repository
     * @param string $branch The branch
     * @return string "<repo>" or "<repo> (branch <branch>)" when branch isn't master
     */
    public static function getRepositoryAndBranch ($repo = "", $branch = "") {
        if ($repo === "") {
            return "";
        }

        if (starts_with($branch, "refs/heads/")) {
            $branch = substr($branch, 11);
        }

        if ($branch === "" || $branch === "master") {
            return $repo;
        }

        return "$repo (branch $branch)";
    }

    /**
     * Gets the title of the head commit
     *
     * @return string
     */
    private function getHeadCommitTitle () {
        return static::getCommitTitle($this->payload->head_commit->message);
    }

    /**
     * Extracts the commit title from the whole commit message.
     *
     * @param string $message The commit message
     * @return string The commit title
     */
    public static function getCommitTitle ($message) {
        // Discards extra lines
        $pos = strpos($message, "\n");
        if ($pos > 0) {
            $message = substr($message, 0, $pos);
        }

        // Short messages are returned as is
        $len = strlen($message);
        if ($len <= 72) {
            return $message;
        }

        // Longer messages are truncated
        return substr($message, 0, 71) . '…';
    }

    /**
     * Gets the description text for the head commit.
     *
     * @return string
     */
    private function getHeadCommitDescription () {
        $commit = $this->payload->head_commit;
        $title = $this->getHeadCommitTitle();
        $committer = $commit->committer->username;
        $author = $commit->author->username;

        $message = "$committer committed $title";
        if ($committer !== $author) {
            $message .= " (authored by $author)";
        }

        return $message;
    }

    /**
     * Gets a short textual description of the event
     *
     * @return string
     */
    public function getDescription () {
        switch ($this->event) {
            case "create":
                $repository = $this->payload->repository->full_name;
                $type = $this->payload->ref_type;
                $ref = $this->payload->ref;

                if ($type == "tag" || $type == "branch") {
                    return "New $type on $repository: $ref";
                }

                return "Unknown create: $type $ref";

            case "ping":
                $quote = $this->payload->zen;
                return "« $quote » — GitHub Webhooks ping zen aphorism.";

            case "push":
                $n = count($this->payload->commits);
                if ($n == 1) {
                    return $this->getHeadCommitDescription();
                }

                $repoAndBranch = $this->getWhere();
                $user = $this->payload->pusher->name;
                return "$user pushed $n commits to $repoAndBranch";

            case "repository":
                $repository = $this->payload->repository->full_name;
                $message = "New repository $repository";
                if ($this->payload->repository->fork) {
                    $message .= " (fork)";
                }
                if ($description = $this->payload->repository->description) {
                    $message .= " — $description";
                }
                return $message;

            default:
                return "Some $this->event happened";
        }
    }

    /**
     * Gets a link to view the event on GitHub
     *
     * @return string The most relevant URL
     */
    public function getLink () {
        switch ($this->event) {
            case "create":
                $type = $this->payload->ref_type;
                $ref = $this->payload->ref;
                $url = $this->payload->repository->html_url;

                if ($type == "tag") {
                    $url .= "/releases/tag/" . $ref;
                } elseif ($type == "branch") {
                    $url .= "/tree/" . $ref;
                }

                return $url;

            case "push":
                $n = count($this->payload->commits);
                if ($n == 1) {
                    return $this->payload->head_commit->url;
                }
                return $this->payload->compare;

            case "repository":
                return $this->payload->repository->html_url;

            default:
                return "";
        }
    }
}
