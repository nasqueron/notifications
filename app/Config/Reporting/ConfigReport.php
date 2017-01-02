<?php

namespace Nasqueron\Notifications\Config\Reporting;

use Nasqueron\Notifications\Config\Features;

use Config;
use Services;

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
     * @return string[]
     */
    protected function queryGates () : array {
        return Config::get('gate.controllers');
    }

    /**
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
