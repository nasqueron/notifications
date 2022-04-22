<?php

namespace Nasqueron\Notifications\Tests;

class PlaceholderTest extends TestCase {
    /**
     * Placeholder homepage works.
     *
     * @return void
     */
    public function testPlaceholder() {
        $this->visit('/')
             ->see('Notifications center');
    }
}
