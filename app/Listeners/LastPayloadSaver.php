<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Events\Event;

class LastPayloadSaver {
    ///
    /// Events handling
    ///

    /**
     * Handles payload events
     */
    public function onPayload (Event $event) : void {
        self::savePayload($event->payload);
    }

    /**
     * Saves payload to log file
     *
     * @param mixed $payload The payload to save
     */
    public static function savePayload ($payload) : void {
        $filename = storage_path('logs/payload.json');
        $content  = json_encode($payload);
        file_put_contents($filename, $content);
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
        $ns = 'Nasqueron\Notifications\Events';
        $class = 'Nasqueron\Notifications\Listeners\LastPayloadSaver';
        $eventsToListen = [
            'DockerHubPayloadEvent',
            'GitHubPayloadEvent',
            'JenkinsPayloadEvent',
            'PhabricatorPayloadEvent',
        ];

        foreach ($eventsToListen as $event) {
            $events->listen("$ns\\$event", "$class@onPayload");
        }
    }
}
