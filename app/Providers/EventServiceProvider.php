<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\{
    Contracts\Events\Dispatcher as DispatcherContract,
    Foundation\Support\Providers\EventServiceProvider as ServiceProvider
};

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
     * @return void
     */
    public function boot() {
        $this->subscribeListeners();
        parent::boot();
    }
}
