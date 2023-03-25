<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory;

class PhabricatorAPIServiceProvider extends ServiceProvider {

    /**
     * Bootstraps the application services.
     */
    public function boot() : void {
    }

    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('phabricator-api', static function () {
            return new PhabricatorAPIFactory;
        });
    }
}
