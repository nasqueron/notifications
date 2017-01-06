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
     */
    public function handle() : void {
        $notification = $this->createNotification();
        Event::fire(new NotificationEvent($notification));
    }

    /**
     * Creates a DockerHub notification
     *
     * @param DockerHubPayloadEvent $event
     * @return \Nasqueron\Notifications\Notifications\Notification The notification
     */
    protected function createNotification() : DockerHubNotification {
        return new DockerHubNotification(
            $this->event->door,          // project
            $this->event->event,        // event type
            $this->event->payload      // raw content
        );
    }
}
