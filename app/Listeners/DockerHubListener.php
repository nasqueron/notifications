<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Facades\DockerHub;
use Nasqueron\Notifications\Jobs\TriggerDockerHubBuild;

/**
 * Listens to events Docker Hub is interested by.
 */
class DockerHubListener {

    ///
    /// GitHub → Docker Hub
    ///

    /**
     * Handles payload events.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function handle (GitHubPayloadEvent $event) : void {
        if ($this->shouldNotify($event)) {
            $this->notifyNewCommits($event);
        }
    }

    /**
     * Determines if the event should be notified to Docker Hub.
     * We're interested by push events, for repos with Docker images
     * we've a token to trigger a build.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     * @return bool
     */
    public function shouldNotify (GitHubPayloadEvent $event) : bool {
        return $event->event === 'push'
            && DockerHub::hasToken($this->getRepository($event));
    }

    /**
     * Notifies Docker Hub to rebuild image.
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function notifyNewCommits (GitHubPayloadEvent $event) : void {
        $job = new TriggerDockerHubBuild($this->getRepository($event));
        $job->handle();
    }

    /**
     * Extracts repository fullname (e.g. acme/foo) from event.
     *
     * @var string
     */
    private function getRepository (GitHubPayloadEvent $event) : string {
        return $event->payload->repository->full_name;
    }
}
