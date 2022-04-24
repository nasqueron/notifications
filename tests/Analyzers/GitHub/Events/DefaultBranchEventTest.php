<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\DefaultBranchEvent;
use Nasqueron\Notifications\Tests\TestCase;

class DefaultBranchEventTest extends TestCase {

   /**
     * @var DefaultBranchEvent
     */
    private $event;

    public function setUp () : void {
        $payload = new \stdClass;
        $payload->repository = new \stdClass;
        $payload->repository->full_name = 'baxterthehacker/public-repo';
        $payload->repository->html_url = 'https://github.com/baxterthehacker/public-repo';
        $payload->repository->default_branch = 'main';
        $payload->action = 'edited';
        $payload->changes = new \stdClass;
        $payload->changes->default_branch = new \stdClass;
        $payload->changes->default_branch->from = 'master';

        $this->event = new DefaultBranchEvent($payload);

        parent::setUp();
    }

    public function testGetDescription () {
        $this->assertSame(
            "baxterthehacker/public-repo default branch is now 'main' instead of 'master'.",
            $this->event->getDescription()
        );
    }

    public function testGetLink () {
        $this->assertSame(
            "https://github.com/baxterthehacker/public-repo",
            $this->event->getLink()
        );
    }

}
