<?php

namespace Nasqueron\Notifications\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Tests\TestCase;

class GitHubGateControllerTest extends TestCase {
    /**
     * GitHub gate works.
     *
     * @return void
     */
    public function testGet () {
        $this->visit('/gate/GitHub')
             ->see('POST');
    }
}
