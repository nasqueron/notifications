<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Actions\Action;
use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\ReportEvent;
use Nasqueron\Notifications\Analyzers\GitHubPayloadAnalyzer;
use Nasqueron\Notifications\Jobs\SendMessageToBroker;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AMQPEventListener {
    ///
    /// GitHub events
    ///

    /**
     * Gets routing key, to allow consumers to select the topic they subscribe to.
     *
     * @param GitHubPayloadEvent $event the payload event
     */
    protected static function getRoutingKey (GitHubPayloadEvent $event) {
       $key = [
           strtolower($event->door),
           self::getGroup($event),
           $event->event
       ];
       return implode('.', $key);
    }

    protected static function getAnalyzer (GitHubPayloadEvent $event) {
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
    protected static function getGroup (GitHubPayloadEvent $event) {
        $analyzer = self::getAnalyzer($event);
        return $analyzer->getGroup();
    }

    /**
     * Handles a GitHub payload event.
     *
     * @param GitHubPayloadEvent $event
     * @return void
     */
    public function onGitHubPayload(GitHubPayloadEvent $event) {
        $message = json_encode($event->payload);
        $routingKey = static::getRoutingKey($event);

        $job = new SendMessageToBroker($routingKey, $message);
        $job->handle();
    }

    ///
    /// Events listening
    ///

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe ($events) {
        $class = 'Nasqueron\Notifications\Listeners\AMQPEventListener';
        $events->listen(
            'Nasqueron\Notifications\Events\GitHubPayloadEvent',
            "$class@onGitHubPayload"
        );
    }
}
