<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\RepositoryEvent;
use Nasqueron\Notifications\Tests\TestCase;

class RepositoryEventTest extends TestCase {

    /**
     * @var \stdClass
     */
    private $payload;

    public function setUp (): void {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/repository.json";
        $this->payload = json_decode(file_get_contents($filename));

        parent::setUp();
    }

    public function testWhenRepositoryIsForked () {
        $payload = clone $this->payload;
        $payload->repository->fork = true;
        $event = new RepositoryEvent($payload);

        $this->assertStringContainsString("fork", $event->getDescription());
    }

    public function testWhenRepositoryContainsDescription () {
        $payload = clone $this->payload;
        $payload->repository->description = "Lorem ipsum dolor";
        $event = new RepositoryEvent($payload);

        $this->assertStringContainsString("Lorem ipsum dolor", $event->getDescription());
    }

    public function testWhenRepositoryIsForkedAndContainsDescription () {
        $payload = clone $this->payload;
        $payload->repository->fork = true;
        $payload->repository->description = "Lorem ipsum dolor";
        $event = new RepositoryEvent($payload);

        $this->assertStringContainsString("fork", $event->getDescription());
        $this->assertStringContainsString("Lorem ipsum dolor", $event->getDescription());
    }

    /**
     * @dataProvider payloadDescriptionProvider
     */
    public function testWhenRepositoryPerAction ($action, $description) {
        $this->payload->action = $action;
        $event = new RepositoryEvent($this->payload);
        $this->assertSame($description, $event->getDescription());
    }

    /**
     * Provides actions and descritions for testWhenRepositoryPerAction
     *
     * See https://developer.github.com/v3/activity/events/types/#repositoryevent
     */
    public function payloadDescriptionProvider () {
        return [
            ['created', "New repository baxterandthehackers/new-repository"],
            ['deleted', "Repository baxterandthehackers/new-repository deleted (danger zone)"],
            ['publicized', "Repository baxterandthehackers/new-repository is now public"],
            ['privatized', "Repository baxterandthehackers/new-repository is now private"],
            ['quuxed', "Unknown repository action: quuxed"],
        ];
    }

}
