<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use ProjectsMap;
use Services;

class PhabricatorProjectsMap extends Command {
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
    protected $description = <<<'TXT'
Regenerate the projects map for each Phabricator instances
TXT;

    /**
     * Executes the console command.
     */
    public function handle() : void {
        foreach (Services::getForGate('Phabricator') as $service) {
            $this->info("Querying projects map for " . $service->instance);
            $map = ProjectsMap::fetch($service->door);
            $map->saveToCache();
            $this->table(
                ['PHID', 'Project name'],
                $map->toArray()
            );
        }
    }
}
