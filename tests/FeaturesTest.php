<?php

namespace Nasqueron\Notifications\Tests;

use Nasqueron\Notifications\Config\Features;

class FeaturesTest extends TestCase {
    public function testEnable () {
        // Find it (en vain …)
        $this->assertNotContains('Quux', Features::getEnabled());
        $this->assertFalse(Features::isEnabled('Quux'));

        // Enable it
        Features::enable('Quux');
        $this->assertTrue(Features::isEnabled('Quux'));
        $this->assertContains('Quux', Features::getEnabled());

        // Disable it
        Features::disable('Quux');
        $this->assertFalse(Features::isEnabled('Quux'));

        // Count it
        $this->assertContains('Quux', Features::getAll());
        $this->assertContains('Quux', Features::getAvailable());
        $this->assertNotContains('Quux', Features::getEnabled());

    }
}
