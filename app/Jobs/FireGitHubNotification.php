<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Notifications\GitHubNotification;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FireGitHubNotification extends Job {

    /**
     * @var GitHubPayloadEvent;
     */
    private $event;

    /**
     * Initializes a new instance of FireGitHubNotification
     *
     * @param GitHubPayloadEvent $event The event to notify
     */
    public function __construct (GitHubPayloadEvent $event) {
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
     * Creates a GitHub notification
     *
     * @param GitHubPayloadEvent $event
     * @return \Nasqueron\Notifications\Notifications\Notification The notification
     */
    protected function createNotification() : GitHubNotification {
        return new GitHubNotification(
            $this->event->door,          // project
            $this->event->event,        // event type
            $this->event->payload      // raw content
        );
    }
}
