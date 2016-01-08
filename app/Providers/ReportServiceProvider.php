<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Events\Dispatcher;
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
        $this->app->singleton('report', function ($app) {
            $report = new ActionsReport();
            static::listenToActionsForReport($report, $app['events']);
            return $report;
        });
    }

    /**
     * Listen to actions fired by the application to add to the report
     *
     * @param Nasqueron\Notifications\Actions\ActionsReport $report The report to add actions to
     * @param Illuminate\Events\Dispatcher $events The application events
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
