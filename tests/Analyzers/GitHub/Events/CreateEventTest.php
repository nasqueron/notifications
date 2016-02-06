<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\CreateEvent;
use Nasqueron\Notifications\Tests\TestCase;

class CreateEventTest extends TestCase {
    /**
     * @var CreateEvent
     */
    private $event;

    public function setUp () {
        $payload = new \stdClass;
        $payload->repository = new \stdClass;
        $payload->repository->full_name = 'baxterthehacker/public-repo';
        $payload->repository->html_url = 'https://github.com/baxterthehacker/public-repo';
        $payload->ref_type = 'bookmark';
        $payload->ref = 'quux';

        $this->event = new CreateEvent($payload);

        parent::setUp();
    }

    public function testNonExistingRefType () {
        $this->assertSame(
            "Unknown create reference: bookmark quux",
            $this->event->getDescription()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonExistingRefTypeLinkException () {
        $this->event->getLink();
    }

}
