<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use Nasqueron\Notifications\Phabricator\ProjectsMap;
use Nasqueron\Notifications\Features;

use Config;
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
     *
     * @return void
     */
    public function __construct () {
        parent::__construct();
    }

    ///
    /// Prepare information tables
    ///

    /**
     * Gets the services (defined in credentials.json) as table rows
     *
     * @return array
     */
    protected function getServicesTableRows () {
        $rows = [];
        foreach (Services::get() as $service) {
            if (isset($service->instance)) {
                $instance = $service->instance;
            } else {
                $instance = 'ø';
            }
            $rows[] = [
                $service->gate,
                $service->door,
                $instance,
                $this->getServiveStatus($service)
            ];
        }
        return $rows;
    }

    /**
     * Gets service status
     *
     * @param $service The service to check
     * @return string A description of the issue if something is wrong; otherwise, "✓".
     */
    protected function getServiveStatus ($service) {
        if ($service->gate === 'Phabricator') {
            // Ensure the projects map is cached
            $map = ProjectsMap::fetch($service->instance);
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
    protected function getFeaturesTableRows () {
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
     *
     * @return mixed
     */
    public function handle () {
        $this->printGates();
        $this->printFeatures();
        $this->printServices();
    }

    protected final function printGates () {
        $this->info("Gates:\n");
        foreach (Config::get('gate.controllers') as $gate) {
            $this->line('- ' . $gate);
        }
    }

    protected final function printFeatures () {
        $this->info("\nFeatures:\n");
        $this->table(
            ['Feature', 'Enabled'],
            $this->getFeaturesTableRows()
        );
    }

    protected final function printServices () {
        $this->info("\nServices declared in credentials:\n");
        $this->table(
            ['Gate', 'Door', 'Instance', 'Status'],
            $this->getServicesTableRows()
        );
    }
}
