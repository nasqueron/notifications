<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Notifications\PhabricatorNotification;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FirePhabricatorNotification extends Job {

    /**
     * @var PhabricatorPayloadEvent;
     */
    private $event;

    /**
     * Initializes a new instance of FirePhabricatorNotification
     *
     * @param PhabricatorPayloadEvent $event The event to notify
     */
    public function __construct (PhabricatorPayloadEvent $event) {
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

    protected function createNotification() : PhabricatorNotification {
        return new PhabricatorNotification(
            $this->event->door,         // Project
            $this->event->story        // Story
        );
    }
}
