<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Events\JenkinsPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Notifications\JenkinsNotification;

use Illuminate\Support\Facades\Event;

class FireJenkinsNotification extends Job {

    /**
     * @var JenkinsPayloadEvent
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
     */
    public function handle() : void {
        $notification = $this->createNotification();
        if ($notification->shouldNotify()) {
            Event::dispatch(new NotificationEvent($notification));
        }
    }

    protected function createNotification() : JenkinsNotification {
        return new JenkinsNotification(
            $this->event->door,          // project
            $this->event->payload      // raw content
        );
    }
}
