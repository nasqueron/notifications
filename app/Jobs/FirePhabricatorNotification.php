<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Notifications\PhabricatorNotification;

use Illuminate\Support\Facades\Event;

class FirePhabricatorNotification extends Job {

    /**
     * @var PhabricatorPayloadEvent
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
        Event::dispatch(new NotificationEvent($notification));
    }

    protected function createNotification() : PhabricatorNotification {
        return new PhabricatorNotification(
            $this->event->door,         // Project
            $this->event->story        // Story
        );
    }
}
