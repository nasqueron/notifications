<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Config;

class EventServiceProvider extends ServiceProvider {

    /**
     * Registers all our listeners as subscriber classes
     */
    private function subscribeListeners () {
        $this->subscribe += Config::get('app.listeners');
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
