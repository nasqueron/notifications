<?php

namespace Nasqueron\Notifications\Analyzers;

use Config;

class GitHubPayloadAnalyzer {
    ///
    /// Private members
    ///

    /**
     * The project name, used to load specific configuration and offer defaults
     * @var string
     */
    private $project;

    /**
     * The GitHub event triggering this request
     * @var string
     */
    private $event;

    /**
     * The request content, as a structured data
     * @var stdClass
     */
    private $payload;


    /**
     * The configuration for the payload analyzer
     * @var GitHubPayloadAnalyzerConfiguration;
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
    public function __construct($project, $event, $payload) {
        $this->project = $project;
        $this->event = $event;
        $this->payload = $payload;

        $this->loadConfiguration($project);
    }

    ///
    /// Configuration
    ///

    const CONFIG_DEFAULT_FILE = 'default.json';

    public function getConfigurationFileName () {
        $dir = Config::get('services.github.analyzer.configDir');
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
            new GitHubPayloadAnalyzerConfiguration()
        );
    }

    ///
    /// Properties
    ///

    public function getRepository () {
        if ($this->isAdministrativeEvent()) {
            return '';
        }
        return $this->payload->repository->name;
    }

    ///
    /// Qualification of the payload
    ///

    public function isAdministrativeEvent () {
        $administrativeEvents = [
            'membership',    // Member added to team
            'ping',          // Special ping ponf event, fired on new hook
            'repository',    // Repository created
        ];

        return in_array($this->event, $administrativeEvents);
    }

    /**
     * Gets the group for a specific payload
     *
     * @return string the group, central part of the routing key
     */
    public function getGroup () {
        // Some events are organization-level only and can't be mapped to an
        // existing repository.
        if ($this->isAdministrativeEvent()) {
            return $this->configuration->administrativeGroup;
        }

        // If the payload is about some repository matching a table of
        // symbols, we need to sort it to the right group.
        $repository = $this->getRepository();
        foreach ($this->configuration->repositoryMapping as $mapping) {
            if ($mapping->doesRepositoryBelong($repository)) {
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

            case "status":
                 return $this->payload->description
                    . " — "
                    . $this->payload->context;

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

            case "status":
                return $this->payload->target_url;

            default:
                return "";
        }
    }
}
