<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Tests\TestCase;

class AMQPActionTest extends TestCase {

    protected $action;

    public function setUp () {
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
