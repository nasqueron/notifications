<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer;
use Nasqueron\Notifications\Jobs\SendMessageToBroker;
use Nasqueron\Notifications\Notifications\Notification;

use Config;

class AMQPEventListener {
    ///
    /// GitHub events
    ///

    /**
     * Gets routing key, to allow consumers to select the topic they subscribe to.
     *
     * @param GitHubPayloadEvent $event the payload event
     */
    protected static function getGitHubEventRoutingKey (GitHubPayloadEvent $event) : string {
       $key = [
           strtolower($event->door),
           self::getGroup($event),
           $event->event
       ];
       return implode('.', $key);
    }

    protected static function getAnalyzer (GitHubPayloadEvent $event) : GitHubPayloadAnalyzer {
        return new GitHubPayloadAnalyzer(
            $event->door,
            $event->event,
            $event->payload
        );
    }

    /**
     * Gets the group for a specific payload
     *
     * @return string the group, central part of the routing key
     */
    protected static function getGroup (GitHubPayloadEvent $event) : string {
        $analyzer = self::getAnalyzer($event);
        return $analyzer->getGroup();
    }

    /**
     * Handles a GitHub payload event.
     *
     * @param GitHubPayloadEvent $event
     */
    public function onGitHubPayload(GitHubPayloadEvent $event) : void {
        $this->sendRawPayload($event);
    }

    /**
     * This is our gateway GitHub Webhooks -> Broker
     *
     * @param GitHubPayloadEvent $event
     */
    protected function sendRawPayload(GitHubPayloadEvent $event) : void {
        $target = Config::get('broker.targets.github_events');
        $routingKey = static::getGitHubEventRoutingKey($event);
        $message = json_encode($event->payload);

        $job = new SendMessageToBroker($target, $routingKey, $message);
        $job->handle();
    }

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
            'Nasqueron\Notifications\Events\GitHubPayloadEvent',
            "$class@onGitHubPayload"
        );
        $events->listen(
            'Nasqueron\Notifications\Events\NotificationEvent',
            "$class@onNotification"
        );
    }
}
