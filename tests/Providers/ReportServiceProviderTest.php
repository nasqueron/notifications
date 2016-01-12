<?php

namespace Nasqueron\Notifications\Tests\Providers;

class ReportServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Nasqueron\Notifications\Actions\ActionsReport',
            'report'
        );
    }

    ///
    /// Tests specific to this service provider
    ///

    public function testEvents () {
        $dispatcher = $this->app->make('events');
        $type = 'Nasqueron\Notifications\Events\ReportEvent';

        // Currently, we don't have listener for ReportEvent.
        $this->assertFalse($dispatcher->hasListeners($type));

        // When we resolve an instance of our object in the container, we have.
        $this->app->make('report');
        $this->assertTrue($dispatcher->hasListeners($type));
    }

}
