<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class GitHubGateControllerTest extends TestCase {
    /**
     * Status works.
     *
     * @return void
     */
    public function testGet () {
        $this->visit('/gate/GitHub')
             ->see('POST');
    }
}
