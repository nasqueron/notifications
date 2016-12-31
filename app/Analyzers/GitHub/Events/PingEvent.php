<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * PingEvent payload analyzer
 *
 * @link https://developer.github.com/webhooks/#ping-event
 */
class PingEvent extends Event {

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () : string {
        return trans(
            'GitHub.EventsDescriptions.PingEvent',
            [
                'zen'     => $this->payload->zen,
                'hook_id' => $this->payload->hook_id,
            ]
        );
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () : string {
        return '';
    }
}
