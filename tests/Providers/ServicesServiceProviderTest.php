<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Nasqueron\Notifications\Providers\ServicesServiceProvider;

use Config;

class ServicesServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            "Nasqueron\Notifications\Config\Services\Services",
            'services'
        );
    }

    ///
    /// Tests specific to this service provider
    ///

    public function testWithCredentialsFile () {
        $services = $this->app->make('services');

        $this->assertGreaterThan(0, count($services->services));
    }

    public function testWithoutCredentialsFile () {
        Config::set('services.gate.credentials', null);
        $services = $this->app->make('services');

        $this->assertSame(0, count($services->services));
    }

    public function testWithNontFoundCredentialsFile () {
        Config::set('services.gate.credentials', 'notfound.json');
        $services = $this->app->make('services');

        $this->assertSame(0, count($services->services));
    }
}
