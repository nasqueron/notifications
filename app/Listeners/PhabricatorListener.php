<?php

namespace Nasqueron\Notifications\Listeners;

/**
 * Verified:
 */

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Jobs\NotifyNewCommitsToDiffusion;

use Illuminate\Events\Dispatcher;

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
    public function onGitHubPayload (GitHubPayloadEvent $event) {
        if ($event->event === 'push') {
            $this->notifyNewCommits($event);
        }
    }

    /**
     * Notifies Phabricator there are new commits to pull.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function notifyNewCommits (GitHubPayloadEvent $event) {
        $job = new NotifyNewCommitsToDiffusion(
            $event->door,
            $event->payload->repository->clone_url
        );
        $job->handle();
    }

    ///
    /// Events listening
    ///

    /**
     * Registers the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe (Dispatcher $events) {
        $class = PhabricatorListener::class;
        $events->listen(
            GitHubPayloadEvent::class,
            "$class@onGitHubPayload"
        );
    }
}
