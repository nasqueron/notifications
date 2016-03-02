<?php

namespace Nasqueron\Notifications\Tests\Providers;

class SentryServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Raven_Client',
            'raven'
        );
    }

}
