<?php

namespace Nasqueron\Notifications\Events;

use Nasqueron\Notifications\Notification;
use Nasqueron\Notifications\Events\Event;
use Illuminate\Queue\SerializesModels;

class NotificationEvent extends Event {
    use SerializesModels;

    /**
     * @var \Nasqueron\Notifications\Notification
     */
    public $notification;

    /**
     * Creates a new event instance.
     *
     * @param Notification $notification the notification
     */
    public function __construct(Notification $notification) {
        $this->notification = $notification;
    }
}
