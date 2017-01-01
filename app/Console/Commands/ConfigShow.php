<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use Nasqueron\Notifications\Config\Features;
use Nasqueron\Notifications\Services\Service;

use Config;
use ProjectsMap;
use Services;

class ConfigShow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show notifications center configuration';

    /**
     * Creates a new command instance.
     */
    public function __construct () {
        parent::__construct();
    }

    ///
    /// Prepare information tables
    ///

    /**
     * Gets the services (defined in credentials.json) as table rows.
     *
     * @return \Nasqueron\Notifications\Services\Service[]
     */
    protected function getServicesTableRows () : array {
        $rows = [];
        foreach (Services::get() as $service) {
            $rows[] = [
                $service->gate,
                $service->door,
                $service->getInstanceName(),
                $this->getServiveStatus($service)
            ];
        }
        return $rows;
    }

    /**
     * Gets service status.
     *
     * @param \Nasqueron\Notifications\Services\Service $service The service to check
     * @return string A description of the issue if something is wrong; otherwise, "✓".
     */
    protected function getServiveStatus (Service $service) : string {
        if ($service->gate === 'Phabricator') {
            // Ensure the projects map is cached
            $map = \ProjectsMap::fetch($service->door);
            if (!$map->isCached()) {
                return "Projects map not cached.";
            }
        }

        return "✓";
    }

    /**
     * Gets features as table rows
     *
     * @return array
     */
    protected function getFeaturesTableRows () : array {
        $rows = [];
        foreach (Features::getAll() as $key => $value) {
            if ($value) {
                $checkMark = '✓';
            } else {
                $checkMark = '';
            }

            $rows[] = [$key, $checkMark];
        }
        return $rows;
    }

    ///
    /// Handle the command
    ///

    /**
     * Executes the console command.
     */
    public function handle () : void {
        $this->printGates();
        $this->printFeatures();
        $this->printServices();
    }

    protected final function printGates () : void {
        $this->info("Gates:\n");
        foreach (Config::get('gate.controllers') as $gate) {
            $this->line('- ' . $gate);
        }
    }

    protected final function printFeatures () : void {
        $this->info("\nFeatures:\n");
        $this->table(
            ['Feature', 'Enabled'],
            $this->getFeaturesTableRows()
        );
    }

    protected final function printServices () : void {
        $this->info("\nServices declared in credentials:\n");
        $this->table(
            ['Gate', 'Door', 'Instance', 'Status'],
            $this->getServicesTableRows()
        );
    }
}
