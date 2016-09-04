<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Actions\TriggerDockerHubBuildAction;
use Nasqueron\Notifications\Tests\TestCase;

class TriggerDockerHubBuildActionTest extends TestCase {

    protected $action;

    public function setUp () {
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
