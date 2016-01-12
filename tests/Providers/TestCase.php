<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Nasqueron\Notifications\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase {

    /**
     * Asserts a service in the application container is from the expected type.
     *
     * @param $expectedType The type to check
     * @param $serviceName The service name to use as application container key
     */
    public function assertServiceInstanceOf ($expectedType, $serviceName) {
        $service = $this->app->make($serviceName);
        $this->assertInstanceOf($expectedType, $service);
    }

}
