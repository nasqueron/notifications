<?php

namespace Nasqueron\Notifications\Events;

use Nasqueron\Notifications\Events\Event;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;
use Illuminate\Queue\SerializesModels;

class PhabricatorPayloadEvent extends Event {
    use SerializesModels;

    /**
     * The gate door which receives the request
     * @var string
     */
    public $door;

    /**
     * The raw payload
     * @var iterable
     */
    public $payload;

    /**
     * The story sent by the request
     * @var PhabricatorStory
     */
    public $story;

    /**
     * Gets story from the request
     *
     * @return PhabricatorStory
     */
    protected function getStory () {
        return PhabricatorStory::loadFromIterable(
            $this->door,
            $this->payload
        );
    }

    /**
     * Creates a new event instance.
     *
     * @param string $door
     * @param iterable $payload
     */
    public function __construct(string $door, iterable $payload) {
        $this->door = $door;
        $this->payload = $payload;

        $this->story = $this->getStory();
    }
}
