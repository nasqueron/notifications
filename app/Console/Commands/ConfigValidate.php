<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\App;

class ConfigValidate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validates JSON configuration files';

    private function getFS () : FilesystemAdapter {
        return App::make('filesystem')->disk('local');
    }

    private function getConfigFiles () : array {
        return array_filter(
            $this->getFS()->allFiles(),

            // Filters *.json
            function ($file) : bool {
                return substr($file, -5) === ".json";
            }
        );
    }

    /**
     * Executes the console command.
     */
    public function handle() : void {
        $files = $this->getConfigFiles();

        foreach ($files as $file) {
            $content = $this->getFS()->get($file);
            if (json_decode($content) === null) {
                $this->line("$file — " . json_last_error_msg());
            }
        }
    }
}
