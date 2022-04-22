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
     */
    public function getWhere () : string {
        $repo = $this->payload->repository->name;
        $branch = $this->payload->ref;

        return static::getRepositoryAndBranch($repo, $branch);
    }

    public static function getRepositoryAndBranch (
        $repo = "",
        $branch = ""
    ) : string {
        if ($repo === "") {
            return "";
        }

        if (str_starts_with($branch, "refs/heads/")) {
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
