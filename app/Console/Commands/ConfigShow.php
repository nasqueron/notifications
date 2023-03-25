<?php

namespace Nasqueron\Notifications\Console\Commands;

use Illuminate\Console\Command;

use Nasqueron\Notifications\Config\Reporting\ConfigReport;

class ConfigShow extends Command {

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
     * @var \Nasqueron\Notifications\Config\Reporting\ConfigReport
     */
    private $report;

    ///
    /// Prepare information tables
    ///

    /**
     * Gets the services (defined in credentials.json) as table rows.
     *
     * @return string[][]
     */
    protected function getServicesTableRows () : array {
        $rows = [];

        foreach ($this->report->services as $service) {
            $rows[] = $service->toFancyArray();
        }

        return $rows;
    }

    /**
     * Gets features as table rows
     *
     * @return string[][]
     */
    protected function getFeaturesTableRows () : array {
        $rows = [];

        foreach ($this->report->features as $feature) {
            $rows[] = $feature->toFancyArray();
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
        $this->prepareReport();

        $this->printGates();
        $this->printFeatures();
        $this->printServices();
    }

    protected final function prepareReport() : void {
        $this->report = new ConfigReport();
    }

    protected final function printGates () : void {
        $this->info("Gates:\n");
        foreach ($this->report->gates as $gate) {
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
