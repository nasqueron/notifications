<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Actions\ActionsReport;

class ReportServiceProvider extends ServiceProvider {
    /**
     * Registers the application services.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function register() {
        $this->app->singleton('report', function ($app) {
            return new ActionsReport();
        });
    }
}
