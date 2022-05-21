<?php

namespace Nasqueron\Notifications\Events;

use Illuminate\Queue\SerializesModels;

class JenkinsPayloadEvent extends Event {
    use SerializesModels;

    /**
     * The gate door which receives the request
     * @var string
     */
    public $door;

    /**
     * The request content, as a structured data
     * @var \stdClass
     */
    public $payload;

    /**
     * Creates a new event instance.
     *
     * @param string    $door
     * @param \stdClass $payload
     */
    public function __construct(string $door, \stdClass $payload) {
        $this->door = $door;
        $this->payload = $payload;
    }
}
