<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Notifications\DockerHubNotification;
use Nasqueron\Notifications\Events\DockerHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FireDockerHubNotification extends Job {

    /**
     * @var DockerHubPayloadEvent;
     */
    private $event;

    /**
     * Initializes a new instance of FireDockerHubNotification
     *
     * @param DockerHubPayloadEvent $event The event to notify
     */
    public function __construct (DockerHubPayloadEvent $event) {
        $this->event = $event;
    }

    ///
    /// Task
    ///

    /**
     * Executes the job.
     *
     * @return void
     */
    public function handle() {
        $notification = $this->createNotification();
        Event::fire(new NotificationEvent($notification));
    }

    /**
     * Creates a DockerHub notification
     *
     * @param DockerHubPayloadEvent $event
     * @return Notification the notification
     */
    protected function createNotification() {
        return new DockerHubNotification(
            $this->event->door,          // project
            $this->event->event,        // event type
            $this->event->payload      // raw content
        );
    }
}
