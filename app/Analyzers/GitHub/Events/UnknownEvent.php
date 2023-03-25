<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * Unknown event payload analyzer
 *
 * This is a fallack when no specific class exists for this event type.
 */
class UnknownEvent extends Event {

    /**
     * @var string
     */
    private $eventType;

    /**
     * Initializes a new instance of the UnknownEvent class, an Event analyzer
     * class to handle unknown events type.
     *
     * @param string $eventType The event type (e.g. push)
     */
    public function __construct ($eventType, $payload = null) {
        $this->eventType = $eventType;
        parent::__construct($payload);
    }

    /**
     * Gets description for the payload
     */
    public function getDescription () : string {
        return "Some $this->eventType happened";
    }

    /**
     * Gets link for the payload
     */
    public function getLink () : string {
        return "";
    }
}
