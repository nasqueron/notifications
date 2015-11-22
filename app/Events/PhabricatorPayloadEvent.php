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
     * The story sent by the request
     * @var PhabricatorStory
     */
    public $story;

    /**
     * The list of the projects attached to this story
     * @var string[]
     */
    public $projects;

    /**
     * Creates a new event instance.
     *
     * @param string $door
     * @param string $event
     * @param stdClass $payload
     */
    public function __construct($door, PhabricatorStory $story) {
        $this->door = $door;
        $this->story = $story;
        $this->projects = $story->getProjects(); // Cost: up to 3 API calls
    }
}
