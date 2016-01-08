<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use Nasqueron\Notifications\Phabricator\ProjectsMap;

use Services;

class PhabricatorGetProjectsMap extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phabricator:projectsmap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the projects map for each Phabricator instances';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        foreach (Services::getForGate('Phabricator') as $service) {
            $this->info("Querying projects map for " . $service->instance);
            $map = ProjectsMap::fetch($service->instance);
            $map->saveToCache();
            $this->table(
                ['PHID', 'Project name'],
                $map->toArray()
            );
        }
    }
}
