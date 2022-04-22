<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Jobs\NotifyNewCommitsToDiffusion;

/**
 * Listens to events Phabricator is interested by.
 */
class PhabricatorListener {

    ///
    /// GitHub → Phabricator
    ///

    /**
     * Handles payload events.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function handle (GitHubPayloadEvent $event) : void {
        if ($event->event === 'push') {
            $this->notifyNewCommits($event);
        }
    }

    /**
     * Notifies Phabricator there are new commits to pull.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function notifyNewCommits (GitHubPayloadEvent $event) : void {
        $job = new NotifyNewCommitsToDiffusion(
            $event->door,
            $event->payload->repository->clone_url
        );
        $job->handle();
    }
}
