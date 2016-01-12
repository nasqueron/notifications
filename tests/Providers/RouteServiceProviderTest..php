<?php

namespace Nasqueron\Notifications\Tests\Providers;

class BrokerServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Illuminate\Routing\Router',
            'router'
        );
    }

}
