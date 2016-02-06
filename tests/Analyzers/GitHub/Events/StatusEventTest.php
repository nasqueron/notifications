<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\StatusEvent;
use Nasqueron\Notifications\Tests\TestCase;

class StatusEventTest extends TestCase {

    /**
     * @var stdClass
     */
    private $payload;

    public function setUp () {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/status.json";
        $this->payload = json_decode(file_get_contents($filename));

        parent::setUp();
    }

    public function testWhenStatusContainsUrl () {
        $payload = clone $this->payload;
        $payload->target_url = "http://www.perdu.com/";
        $event = new StatusEvent($payload);

        $this->assertSame(
            "http://www.perdu.com/",
            $event->getLink()
        );
    }

}
