<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Notifications\GitHubNotification;

use Event;

class NotificationListener {

    ///
    /// Distill GitHub payloads into notifications
    ///

    /**
     * Handles a GitHub payload event.
     *
     * @param GitHubPayloadEvent $event
     * @return void
     */
    public function onGitHubPayload(GitHubPayloadEvent $event) {
        $notification = $this->createNotification($event);
        $event = new NotificationEvent($notification);
        Event::fire($event);
    }

    /**
     * Creates a GitHub notification
     *
     * @param GitHubPayloadEvent $event
     * @return Notification the notification
     */
    protected function createNotification(GitHubPayloadEvent $event) {
        $notification = new GitHubNotification(
            $event->door,          // project
            $event->event,        // event type
            $event->payload      // raw content
        );

        return $notification;
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
            'Nasqueron\Notifications\Events\GitHubPayloadEvent',
            "$class@onGitHubPayload"
        );
    }

}