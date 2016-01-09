<?php

namespace Nasqueron\Notifications\Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatusTest extends TestCase
{
    /**
     * Status works.
     *
     * @return void
     */
    public function testStatus()
    {
        $this->visit('/status')
             ->see('ALIVE');
    }
}
