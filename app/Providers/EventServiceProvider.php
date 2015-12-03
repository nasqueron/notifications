<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use File;

class EventServiceProvider extends ServiceProvider {

    /**
     * Registers all our listeners as subscriber classes
     */
    private function subscribeListeners () {
        $namespace = Container::getInstance()->getNamespace() . 'Listeners\\';
        $files = File::allFiles(app_path('Listeners'));
        foreach ($files as $file) {
            $class = $namespace . $file->getBasename('.php');
            $this->subscribe[] = $class;
        }
    }

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events) {
        $this->subscribeListeners();
        parent::boot($events);
    }
}
