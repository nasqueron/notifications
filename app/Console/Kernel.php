<?php

namespace Nasqueron\Notifications\Console;

use Illuminate\Console\Command;
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
        \Nasqueron\Notifications\Console\Commands\ConfigValidate::class,
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

    /**
     * Gets a command by name
     *
     * @param string $name The command name (first word of the command signature)
     * @return \Illuminate\Console\Command
     * @throws \RuntimeException when command doesn't exit
     */
    public function get (string $name) : Command {
        $commands = $this->all();

        if (array_key_exists($name, $commands)) {
            return $commands[$name];
        }

        throw new \RuntimeException("Command $name doesn't exist.");
    }

    /**
     * Gets a command by class
     *
     * @param string $class The command class
     * @return \Illuminate\Console\Command
     * @throws \RuntimeException when command doesn't exit
     */
    public function getByClass (string $class) : Command {
        $commands = $this->all();

        foreach ($commands as $command) {
            if ($command instanceof $class) {
                return $command;
            }
        }

        throw new \RuntimeException("Command $class doesn't exist.");
    }
}
