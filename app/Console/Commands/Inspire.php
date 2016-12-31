<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class Inspire extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Executes the console command.
     */
    public function handle() : void {
        $this->comment(PHP_EOL . Inspiring::quote() . PHP_EOL);
    }
}
