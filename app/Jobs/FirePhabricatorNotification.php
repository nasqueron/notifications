<?php

namespace Nasqueron\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Nasqueron\Notifications\Notifications\PhabricatorNotification;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\Job;

use Event;

class FirePhabricatorNotification extends Job implements SelfHandling {

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
     *
     * @return void
     */
    public function handle() {
        $notification = $this->createNotification();
        Event::fire(new NotificationEvent($notification));
    }

    /**
     * Creates a Phabricator notification
     *
     * @param PhabricatorPayloadEvent $event
     * @return Notification the notification
     */
    protected function createNotification() {
        return new PhabricatorNotification(
            $this->event->door,         // Project
            $this->event->story,       // Story
            $this->event->projects    // Phabricator projects
        );
    }
}
