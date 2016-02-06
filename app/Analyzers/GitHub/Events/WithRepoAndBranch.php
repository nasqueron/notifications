<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * Helper methods for events with a need to specify the repo and the branch
 * (e.g. push)
 *
 * @link https://developer.github.com/v3/activity/events/types/#pushevent
 */
trait WithRepoAndBranch {

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

        return trans('GitHub.RepoAndBranch', [
            'repo'   => $repo,
            'branch' => $branch,
        ]);
    }

}
