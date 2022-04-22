<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Tests\TestCase;

class AMQPActionTest extends TestCase {

    protected $action;

    public function setUp (): void {
        $this->action = new AMQPAction(
            'method',
            'target'
        );
    }

    public function testPublicProperties () {
        $this->assertNull($this->action->error);
        $this->assertSame('AMQPAction', $this->action->action);
    }
}
