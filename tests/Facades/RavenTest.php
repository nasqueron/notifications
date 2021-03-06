<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Tests\TestCase;

use Config;
use Raven;

class RavenTest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            'Raven_Client',
            Raven::getFacadeRoot()
        );
    }

    public function testIsConfigured () {
        Config::set("services.sentry.dsn", "something");
        $this->assertTrue(Raven::isConfigured());
    }

    public function testIsConfiguredWhenItIsNot () {
        Config::offsetUnset("services.sentry.dsn");
        $this->assertFalse(Raven::isConfigured());
    }

}
