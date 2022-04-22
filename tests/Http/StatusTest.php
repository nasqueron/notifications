<?php

namespace Nasqueron\Notifications\Tests;

class StatusTest extends TestCase {
    /**
     * Status works.
     *
     * @return void
     */
    public function testStatus() {
        $this->visit('/status')
             ->see('ALIVE');
    }
}
