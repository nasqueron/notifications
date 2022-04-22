<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Nasqueron\Notifications\Actions\NotifyNewCommitsAction;
use Nasqueron\Notifications\Tests\TestCase;

class NotifyNewCommitsActionTest extends TestCase {

    protected $action;

    public function setUp () : void {
        $this->action = new NotifyNewCommitsAction(
            'QUUX'
        );
    }

    public function testPublicProperties () {
        $this->assertNull($this->action->error);
        $this->assertSame('NotifyNewCommitsAction', $this->action->action);
    }
}
