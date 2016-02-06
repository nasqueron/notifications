<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\RepositoryEvent;
use Nasqueron\Notifications\Tests\TestCase;

class RepositoryEventTest extends TestCase {

    /**
     * @var stdClass
     */
    private $payload;

    public function setUp () {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/repository.json";
        $this->payload = json_decode(file_get_contents($filename));

        parent::setUp();
    }

    public function testWhenRepositoryIsForked () {
        $payload = clone $this->payload;
        $payload->repository->fork = true;
        $event = new RepositoryEvent($payload);

        $this->assertContains("fork", $event->getDescription());
    }

    public function testWhenRepositoryContainsDescription () {
        $payload = clone $this->payload;
        $payload->repository->description = "Lorem ipsum dolor";
        $event = new RepositoryEvent($payload);

        $this->assertContains("Lorem ipsum dolor", $event->getDescription());
    }

    public function testWhenRepositoryIsForkedAndContainsDescription () {
        $payload = clone $this->payload;
        $payload->repository->fork = true;
        $payload->repository->description = "Lorem ipsum dolor";
        $event = new RepositoryEvent($payload);

        $this->assertContains("fork", $event->getDescription());
        $this->assertContains("Lorem ipsum dolor", $event->getDescription());
    }

}
