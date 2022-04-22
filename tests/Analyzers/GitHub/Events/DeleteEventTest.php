<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\DeleteEvent;
use Nasqueron\Notifications\Tests\TestCase;

use InvalidArgumentException;

class DeleteEventTest extends TestCase {
    /**
     * @var DeleteEvent
     */
    private $event;

    public function setUp (): void {
        $payload = new \stdClass;
        $payload->repository = new \stdClass;
        $payload->repository->full_name = 'baxterthehacker/public-repo';
        $payload->repository->html_url = 'https://github.com/baxterthehacker/public-repo';
        $payload->ref_type = 'bookmark';
        $payload->ref = 'quux';

        $this->event = new DeleteEvent($payload);

        parent::setUp();
    }

    public function testNonExistingRefType () {
        $this->assertSame(
            "Unknown delete reference: bookmark quux",
            $this->event->getDescription()
        );
    }

    public function testNonExistingRefTypeLinkException () {
        $this->expectException(InvalidArgumentException::class);
        $this->event->getLink();
    }

}
