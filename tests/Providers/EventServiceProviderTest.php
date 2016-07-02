<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Config;
use File;

class EventServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Illuminate\Contracts\Events\Dispatcher',
            'events'
        );
    }

    ///
    /// Tests specific to this service provider
    ///

    public function testOmittedFiles () {
        $subscribe = [];

        $namespace = $this->app->getInstance()->getNamespace() . 'Listeners\\';
        $files = File::allFiles(app_path('Listeners'));
        foreach ($files as $file) {
            $class = $namespace . $file->getBasename('.php');
            $subscribe[] = $class;
        }

        $this->assertEquals(
            $subscribe, Config::get('app.listeners'),
            'The files in the app/Listeners folder and the array of classes ' .
            'defined in config/app.php at listeners key diverge.',
            0.0, 10, true // delta, maxDepth, canonicalize
        );
    }

}
