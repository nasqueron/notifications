<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Phabricator\ProjectsMapFactory;

class PhabricatorProjectsMapServiceProvider extends ServiceProvider {

    /**
     * Bootstraps the application services.
     */
    public function boot() : void {
    }

    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('phabricator-projectsmap', static function () {
            return new ProjectsMapFactory;
        });
    }
}
