<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Jobs\SendMessageToBroker;
use Nasqueron\Notifications\Notifications\Notification;

use Config;

class AMQPEventListener {

    ///
    /// Notifications
    ///

    /**
     * Handles a notification event.
     *
     * @param NotificationEvent $event
     */
    public function onNotification(NotificationEvent $event) : void {
        $this->sendNotification($event);
    }

    /**
     * Gets routing key, to allow consumers to select the topic they subscribe to.
     *
     * @param NotificationEvent $event
     */
    protected static function getNotificationRoutingKey (Notification $notification) : string {
        $key = [
            $notification->project,
            $notification->group,
            $notification->service,
            $notification->type
        ];

        return strtolower(implode('.', $key));
    }

    /**
     * This is our gateway specialized for distilled notifications
     *
     * @param NotificationEvent $event
     */
    protected function sendNotification(NotificationEvent $event) : void {
        $notification = $event->notification;

        $target = Config::get('broker.targets.notifications');
        $routingKey = static::getNotificationRoutingKey($notification);
        $message = json_encode($notification);

        $job = new SendMessageToBroker($target, $routingKey, $message);
        $job->handle();
    }

    ///
    /// Events listening
    ///

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe (\Illuminate\Events\Dispatcher $events) : void {
        $class = 'Nasqueron\Notifications\Listeners\AMQPEventListener';
        $events->listen(
            'Nasqueron\Notifications\Events\NotificationEvent',
            "$class@onNotification"
        );
    }
}
