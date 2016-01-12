<?php

namespace Nasqueron\Notifications\Tests\Providers;

class AppServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Illuminate\Contracts\Foundation\Application',
            'app'
        );
    }

}
