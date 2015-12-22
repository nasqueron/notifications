<?php

namespace Nasqueron\Notifications\Events;

use Nasqueron\Notifications\Events\Event;
use Illuminate\Queue\SerializesModels;

class DockerHubPayloadEvent extends Event {
    use SerializesModels;

    /**
     * The gate door which receives the request
     * @var string
     */
    public $door;

    /**
     * The event triggering this request
     * @var string
     */
    public $event = "push";

    /**
     * The request content, as a structured data
     * @var stdClass
     */
    public $payload;

    /**
     * Creates a new event instance.
     *
     * @param string $door
     * @param stdClass $payload
     */
    public function __construct($door, $payload) {
        $this->door = $door;
        //$this->event = $event; // Currently, the API only send push events
        $this->payload = $payload;
    }
}
