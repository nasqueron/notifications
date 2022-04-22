<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\UnknownEvent;
use Nasqueron\Notifications\Tests\TestCase;

class UnknownEventTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Analyzers\GitHub\Events\UnknownEvent
     */
    private $event;

    public function setUp (): void {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/push.json";
        $payload = json_decode(file_get_contents($filename));
        $this->event =  new UnknownEvent("quux", $payload);

        parent::setUp();
    }

    public function testUnknownEvent () {
        $this->assertInstanceOf("Nasqueron\Notifications\Analyzers\GitHub\Events\UnknownEvent", $this->event);
        $this->assertSame("Some quux happened", $this->event->getDescription());
        $this->assertEmpty($this->event->getLink());
    }
}
