<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Nasqueron\Notifications\Providers\EventServiceProvider;

use Illuminate\Support\Facades\File;

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

        $this->assertEqualsCanonicalizing(
            $subscribe, $this->getRegisteredListeners(),
            'The files in the app/Listeners folder and the array of classes ' .
            'defined in config/app.php at listeners key diverge.'
        );
    }

    private function getRegisteredListeners () : array {
        $provider = $this->app->getProvider(EventServiceProvider::class);
        $eventsMap = $provider->listens();
        $listeners = [];

        foreach ($eventsMap as $foundListeners) {
            foreach ($foundListeners as $listener){
                $listeners[] = $listener;
            }
        }
        return array_unique($listeners);
    }
}
