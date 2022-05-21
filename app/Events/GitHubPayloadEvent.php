<?php

namespace Nasqueron\Notifications\Events;

use Illuminate\Queue\SerializesModels;

class GitHubPayloadEvent extends Event {
    use SerializesModels;

    /**
     * The gate door which receives the request
     * @var string
     */
    public $door;

    /**
     * The GitHub event triggering this request
     * @var string
     */
    public $event;

    /**
     * The request content, as a structured data
     * @var \stdClass
     */
    public $payload;

    /**
     * Creates a new event instance.
     *
     * @param string    $door
     * @param string    $event
     * @param \stdClass $payload
     */
    public function __construct(string $door, string $event, \stdClass $payload) {
        $this->door = $door;
        $this->event = $event;
        $this->payload = $payload;
    }
}
