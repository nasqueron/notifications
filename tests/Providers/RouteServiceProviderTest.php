<?php

namespace Nasqueron\Notifications\Tests\Providers;

class RouteServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Illuminate\Routing\Router',
            'router'
        );
    }

}
