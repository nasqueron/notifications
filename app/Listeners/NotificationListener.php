<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\DockerHubPayloadEvent;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\JenkinsPayloadEvent;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Jobs\FireDockerHubNotification;
use Nasqueron\Notifications\Jobs\FireGitHubNotification;
use Nasqueron\Notifications\Jobs\FireJenkinsNotification;
use Nasqueron\Notifications\Jobs\FirePhabricatorNotification;

class NotificationListener {

    ///
    /// Distill services' payloads into notifications
    ///

    /**
     * Handles a Docker Hub payload event.
     *
     * @param DockerHubPayloadEvent $event
     * @return void
     */
    public function onDockerHubPayload(DockerHubPayloadEvent $event) : void {
        $job = new FireDockerHubNotification($event);
        $job->handle();
    }

    /**
     * Handles a GitHub payload event.
     *
     * @param GitHubPayloadEvent $event
     * @return void
     */
    public function onGitHubPayload(GitHubPayloadEvent $event) : void {
        $job = new FireGitHubNotification($event);
        $job->handle();
    }

    /**
     * Handles a Phabricator payload event.
     *
     * @param PhabricatorPayloadEvent $event
     * @return void
     */
    public function onPhabricatorPayload(
        PhabricatorPayloadEvent $event
    ) : void {
        $job = new FirePhabricatorNotification($event);
        $job->handle();
    }

    /**
     * Handles a Jenkins payload event.
     *
     * @param JenkinsPayloadEvent $event
     * @return void
     */
    public function onJenkinsPayload (JenkinsPayloadEvent $event) : void {
        $job = new FireJenkinsNotification($event);
        $job->handle();
    }

    ///
    /// Events listening
    ///

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe (\Illuminate\Events\Dispatcher $events) : void {
        $class = 'Nasqueron\Notifications\Listeners\NotificationListener';
        $events->listen(
            'Nasqueron\Notifications\Events\DockerHubPayloadEvent',
            "$class@onDockerHubPayload"
        );
        $events->listen(
            'Nasqueron\Notifications\Events\GitHubPayloadEvent',
            "$class@onGitHubPayload"
        );
        $events->listen(
            'Nasqueron\Notifications\Events\JenkinsPayloadEvent',
            "$class@onJenkinsPayload"
        );
        $events->listen(
            'Nasqueron\Notifications\Events\PhabricatorPayloadEvent',
            "$class@onPhabricatorPayload"
        );

    }

}
