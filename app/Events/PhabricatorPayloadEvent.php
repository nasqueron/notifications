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
     * The Phabricator instance
     * @var string
     */
    protected $instance;

    /**
     * The raw payload
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
     * @param string $instance The Phabricator instance URL
     * @return PhabricatorStory
     */
    protected function getStory () {
        return PhabricatorStory::loadFromArray(
            $this->instance,
            $this->payload
        );
    }

    /**
     * Creates a new event instance.
     *
     * @param string $door
     * @param string $instance The Phabricator instance URL
     * @param stdClass $payload
     */
    public function __construct($door, $instance, $payload) {
        $this->door = $door;
        $this->instance = $instance;
        $this->payload = $payload;

        $this->story = $this->getStory();
    }
}
