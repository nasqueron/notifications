<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Nasqueron\Notifications\Actions\TriggerDockerHubBuildAction;
use Nasqueron\Notifications\Tests\TestCase;

class TriggerDockerHubBuildActionTest extends TestCase {

    protected $action;

    public function setUp () : void {
        $this->action = new TriggerDockerHubBuildAction(
            'acme/foo'
        );
    }

    public function testPublicProperties () {
        $this->assertNull($this->action->error);
        $this->assertSame('acme/foo', $this->action->image);
        $this->assertSame('TriggerDockerHubBuildAction', $this->action->action);
    }
}
