<?php

namespace Nasqueron\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Nasqueron\Notifications\Notifications\GitHubNotification;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FireGitHubNotification extends Job implements SelfHandling {

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
     *
     * @return void
     */
    public function handle() {
        $notification = $this->createNotification();
        Event::fire(new NotificationEvent($notification));
    }

    /**
     * Creates a GitHub notification
     *
     * @param GitHubPayloadEvent $event
     * @return Notification the notification
     */
    protected function createNotification() {
        return new GitHubNotification(
            $this->event->door,          // project
            $this->event->event,        // event type
            $this->event->payload      // raw content
        );
    }
}
