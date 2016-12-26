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
    public function __construct ($payload) {
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
    public function getPayload () {
        return $this->payload;
    }

    /**
     * Gets notification text for this event.
     *
     * @return string
     */
    abstract public function getText();

    /**
     * Gets notification link related to this event.
     *
     * @return string
     */
    abstract public function getLink();

}
