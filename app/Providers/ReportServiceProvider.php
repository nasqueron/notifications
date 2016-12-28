<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Nasqueron\Notifications\Actions\ActionsReport;
use Nasqueron\Notifications\Events\ReportEvent;

class ReportServiceProvider extends ServiceProvider {
    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('report', function (Application $app) {
            $report = new ActionsReport();
            static::listenToActionsForReport($report, $app->make('events'));
            return $report;
        });
    }

    /**
     * Listen to actions fired by the application to add to the report
     *
     * @param \Nasqueron\Notifications\Actions\ActionsReport $report The report to add actions to
     * @param \Illuminate\Contracts\Events\Dispatcher $events The application events dispatcher
     */
    public static function listenToActionsForReport (ActionsReport $report, Dispatcher $events) {
        $events->listen(
            'Nasqueron\Notifications\Events\ReportEvent',
            function (ReportEvent $event) use ($report) {
                $report->addAction($event->action);
            }
        );
    }
}
