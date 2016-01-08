<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Tests\TestCase;

class ActionErrorTest extends TestCase {

    protected $actionError;

    public function setUp () {
        $ex = new \RuntimeException('Lorem ipsum dolor');
        $this->actionError = new ActionError($ex);
    }

    public function testPublicProperties () {
        $this->assertSame('RuntimeException', $this->actionError->type);
        $this->assertSame('Lorem ipsum dolor', $this->actionError->message);
    }
}
