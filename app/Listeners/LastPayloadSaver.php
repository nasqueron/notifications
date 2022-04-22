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
    public function handle (Event $event) : void {
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
}
