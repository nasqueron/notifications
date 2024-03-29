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
     */
    public function register() : void {
        $this->app->singleton('report', function (Application $app) {
            $report = new ActionsReport();
            static::listenToActionsForReport(
                $report,
                $app->make('events')
            );
            return $report;
        });
    }

    public static function listenToActionsForReport (
        ActionsReport $report,
        Dispatcher $events
    ) : void {
        $events->listen(
            'Nasqueron\Notifications\Events\ReportEvent',
            static function (ReportEvent $event) use ($report) {
                $report->addAction($event->action);
            }
        );
    }
}
