<?php

namespace Nasqueron\Notifications\Config\Reporting;

use Nasqueron\Notifications\Config\Features;

use Nasqueron\Notifications\Facades\Services;
use Illuminate\Support\Facades\Config;

class ConfigReport {

    ///
    /// Public properties
    ///

    /**
     * @var string[]
     */
    public $gates;

    /**
     * @var FeatureReportEntry[]
     */
    public $features;

    /**
     * @var ServiceReportEntry[]
     */
    public $services;

    ///
    /// Public constructor
    ///

    public function __construct () {
        $this->gates = $this->queryGates();
        $this->features = $this->queryFeatures();
        $this->services = $this->queryServices();
    }

    ///
    /// Report builder
    ///

    /**
     * Queries information about the features enabled from the configuration.
     *
     * @return string[]
     */
    protected function queryGates () : array {
        return Config::get('gate.controllers');
    }

    /**
     * Queries information about the features enabled from the configuration.
     *
     * @return FeatureReportEntry[]
     */
    protected function queryFeatures () : array {
        $features = [];

        foreach (Features::getAll() as $feature => $enabled) {
            $features[] = new FeatureReportEntry($feature, $enabled);
        }

        return $features;
    }

    /**
     * Queries information about services described in credentials.json.
     *
     * @return ServiceReportEntry[]
     */
    protected function queryServices () : array {
        $services = [];

        foreach (Services::get() as $service) {
            $services[] = new ServiceReportEntry($service);
        }

        return $services;
    }

}
