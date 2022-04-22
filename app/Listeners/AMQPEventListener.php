<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\SendMessageToBroker;
use Nasqueron\Notifications\Notifications\Notification;

use Illuminate\Support\Facades\Config;

class AMQPEventListener {

    ///
    /// Notifications
    ///

    /**
     * Handles a notification event.
     *
     * @param NotificationEvent $event
     */
    public function handle (NotificationEvent $event) : void {
        $this->sendNotification($event->notification);
    }

    protected static function getNotificationRoutingKey (
        Notification $notification
    ) : string {
        $keyParts = [
            $notification->project,
            $notification->group,
            $notification->service,
            $notification->type,
        ];

        return strtolower(implode('.', $keyParts));
    }

    /**
     * Sends the notification to the broker target for distilled notifications.
     *
     * @param Notification The notification to send
     */
    protected function sendNotification(Notification $notification) : void {
        $target = Config::get('broker.targets.notifications');
        $routingKey = static::getNotificationRoutingKey($notification);
        $message = json_encode($notification);

        $job = new SendMessageToBroker($target, $routingKey, $message);
        $job->handle();
    }
}
