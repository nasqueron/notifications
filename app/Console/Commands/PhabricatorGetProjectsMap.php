<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use Nasqueron\Notifications\Phabricator\ProjectsMap;

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
    protected $description = 'Regerate the projects map for each Phabricator instances';

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
        foreach ($this->getServicesCredentials() as $service) {
            if ($service->gate == "Phabricator") {
                $this->info("Querying projects map for " . $service->instance);
                $map = ProjectsMap::fetch(
                    $service->instance,
                    $service->secret
                );
                $map->save();
                $this->table(
                    ['PHID', 'Project name'],
                    $map->toArray()
                );
            }
        }
    }

    /**
     * Gets service credentials
     *
     * @return stdClass the services credentials
     */
    protected function getServicesCredentials () {
        $path = config('services.gate.credentials');
        $data = json_decode(file_get_contents($path));
        return $data->services;
    }
}
