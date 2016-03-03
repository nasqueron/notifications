<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory;

class PhabricatorAPIServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('phabricator-api', function () {
            return new PhabricatorAPIFactory;
        });
    }
}
