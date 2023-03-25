<?php

namespace Nasqueron\Notifications\Analyzers\DockerHub;

abstract class BaseEvent {

    /**
     * @var \stdClass
     */
    protected $payload;

    /**
     * Initializes a new instance of the BaseEvent object.
     *
     * @param \stdClass $payload The payload to analyze
     */
    public function __construct (\stdClass $payload) {
        $this->payload = $payload;
    }

    ///
    /// Public methods
    ///

    /**
     * Gets notification payload.
     *
     * This method allows analyzer to edit the payload.
     */
    public function getPayload () : \stdClass {
        return $this->payload;
    }

    /**
     * Gets notification text for this event.
     */
    abstract public function getText() : string;

    /**
     * Gets notification link related to this event.
     */
    abstract public function getLink() : string;

}
