<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Notifications\JenkinsNotification;
use Nasqueron\Notifications\Events\JenkinsPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FireJenkinsNotification extends Job {

    /**
     * @var JenkinsPayloadEvent;
     */
    private $event;

    /**
     * Initializes a new instance of FireJenkinsNotification
     *
     * @param JenkinsPayloadEvent $event The event to notify
     */
    public function __construct (JenkinsPayloadEvent $event) {
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
        if ($notification->shouldNotify()) {
            Event::fire(new NotificationEvent($notification));
        }
    }

    /**
     * Creates a Jenkins notification
     *
     * @param JenkinsPayloadEvent $event
     * @return \Nasqueron\Notifications\Notification The notification
     */
    protected function createNotification() {
        return new JenkinsNotification(
            $this->event->door,          // project
            $this->event->payload      // raw content
        );
    }
}
