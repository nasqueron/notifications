<?php

namespace Nasqueron\Notifications\Events;

use Nasqueron\Notifications\Events\Event;
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
     * @param string $door
     * @param \stdClass $payload
     */
    public function __construct($door, $payload) {
        $this->door = $door;
        $this->payload = $payload;
    }
}
