<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\DockerHubPayloadEvent;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Jobs\FireDockerHubNotification;
use Nasqueron\Notifications\Jobs\FireGitHubNotification;
use Nasqueron\Notifications\Jobs\FirePhabricatorNotification;
use Nasqueron\Notifications\Notifications\GitHubNotification;

use Event;

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
    public function onDockerHubPayload(DockerHubPayloadEvent $event) {
        $job = new FireDockerHubNotification($event);
        $job->handle();
    }

    /**
     * Handles a GitHub payload event.
     *
     * @param GitHubPayloadEvent $event
     * @return void
     */
    public function onGitHubPayload(GitHubPayloadEvent $event) {
        $job = new FireGitHubNotification($event);
        $job->handle();
    }

    /**
     * Handles a Phabricator payload event.
     *
     * @param PhabricatorPayloadEvent $event
     * @return void
     */
    public function onPhabricatorPayload(PhabricatorPayloadEvent $event) {
        $job = new FirePhabricatorNotification($event);
        $job->handle();
    }

    ///
    /// Events listening
    ///

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe ($events) {
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
            'Nasqueron\Notifications\Events\PhabricatorPayloadEvent',
            "$class@onPhabricatorPayload"
        );
    }

}
