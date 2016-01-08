<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Actions\NotifyNewCommitsAction;
use Nasqueron\Notifications\Tests\TestCase;

class NotifyNewCommitsActionTest extends TestCase {

    protected $action;

    public function setUp () {
        $this->action = new NotifyNewCommitsAction(
            'QUUX'
        );
    }

    public function testPublicProperties () {
        $this->assertNull($this->action->error);
        $this->assertEquals('NotifyNewCommitsAction', $this->action->action);
    }
}
