<?php

namespace Nasqueron\Notifications\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var string[]
     */
    protected $commands = [
        \Nasqueron\Notifications\Console\Commands\ConfigShow::class,
        \Nasqueron\Notifications\Console\Commands\Inspire::class,
        \Nasqueron\Notifications\Console\Commands\NotificationsPayload::class,
        \Nasqueron\Notifications\Console\Commands\PhabricatorProjectsMap::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule (Schedule $schedule) : void {
        $schedule->command('inspire')
                 ->hourly();
    }
}
