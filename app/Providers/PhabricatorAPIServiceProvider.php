<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory;

class PhabricatorAPIServiceProvider extends ServiceProvider {

    /**
     * Bootstraps the application services.
     *
     * @return void
     */
    public function boot() {
    }

    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('phabricator-api', static function () {
            return new PhabricatorAPIFactory;
        });
    }
}
