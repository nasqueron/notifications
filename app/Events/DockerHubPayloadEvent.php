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
    public $event;

    /**
     * The request content, as a structured data
     * @var stdClass
     */
    public $payload;

    /**
     * Gets event according the kind of payload we receive.
     *
     * @return string
     */
    public function getEvent () {
        if (isset($this->payload->repository->repo_url)) {
            return "push";
        }

        return "buildFailure";
    }

    /**
     * Creates a new event instance.
     *
     * @param string $door
     * @param stdClass $payload
     */
    public function __construct($door, $payload) {
        $this->door = $door;
        $this->payload = $payload;
        $this->event = $this->getEvent();
    }
}
