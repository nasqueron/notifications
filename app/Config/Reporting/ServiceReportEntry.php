<?php

namespace Nasqueron\Notifications\Config\Reporting;

use Nasqueron\Notifications\Config\Services\Service;

use Nasqueron\Notifications\Facades\ProjectsMap;

final class ServiceReportEntry extends BaseReportEntry {

    ///
    /// Private members
    ///

    /**
     * @var Service
     */
    private $service;

    ///
    /// Public properties
    ///

    /**
     * @var string
     */
    public $gate;

    /**
     * @var string
     */
    public $door;

    /**
     * @var string
     */
    public $instance;

    /**
     * @var string
     */
    public $status = "";

    ///
    /// Constructor
    ///

    public function __construct (Service $service) {
        $this->service = $service;
        $this->query();
    }

    ///
    /// Report builder
    ///

    /**
     * Queries the service to fill public properties.
     */
    protected function query () : void {
        // Direct properties
        $this->gate = $this->service->gate;
        $this->door = $this->service->door;
        $this->instance = (string)$this->service->instance;

        // Properties to query with business logic
        $this->status = $this->getServiceStatus();
    }

    /**
     * @return string An issue to fix, or an empty string if all looks good.
     */
    protected function getServiceStatus () : string {
        if ($this->isPhabricatorServiceWithNotCachedProjectsMap()) {
            return "Projects map not cached.";
        }

        return "";
    }

    /**
     * Determines if the service matches the following issue to report:
     *   - service is Phabricator
     *   - instance doesn't have the projects' name/PHID map in cache
     *
     * @return bool
     */
    protected function isPhabricatorServiceWithNotCachedProjectsMap () : bool {
        if ($this->service->gate !== 'Phabricator') {
            return false;
        }

        $map = ProjectsMap::fetch($this->service->door);
        return !$map->isCached();
    }

    ///
    /// Format
    ///

    /**
     * Gets the entry as an array. Formats empty string.
     *
     * @return string[]
     */
    public function toArray () : array {
        return [
            $this->gate,
            $this->door,
            $this->instance,
            $this->status,
        ];
    }

    /**
     * Gets the entry as an array. Formats empty string.
     *
     * @return string[]
     */
    public function toFancyArray () : array {
        return [
            $this->gate,
            $this->door,
            self::fancyString($this->instance, 'ø'),
            self::fancyString($this->status, '✓'),
        ];
    }

}
