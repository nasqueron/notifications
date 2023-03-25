<?php

namespace Nasqueron\Notifications\Config\Services;

use Illuminate\Support\Facades\Storage;

class Services {

    ///
    /// Properties
    ///

    /**
     * @var Service[]
     */
    public $services = [];

    ///
    /// Constructors
    ///

    /**
     * @param string $file The JSON file to deserialize
     * @return Services The deserialized instance
     */
    public static function loadFromJson (string $file) : Services {
        $data = json_decode(Storage::get($file));
        $mapper = new \JsonMapper();

        return $mapper->map($data, new self());
    }

    ///
    /// Methods to get a list of services
    ///

    /**
     * Gets the services found in credentials.json configuration file.
     */
    public function get () : array {
        return $this->services;
    }

    /**
     * Gets all the services for a specific gate.
     *
     * @param string $gate The gate (e.g. GitHub)
     * @return Service[]
     */
    public function getForGate (string $gate) : array {
        $services = [];

        foreach ($this->services as $service) {
            if ($service->gate === $gate) {
                $services[] = $service;
            }
        }

        return $services;
    }

    ///
    /// Methods to find a service matching criteria
    ///

    /**
     * Gets the service for a specific gate and door
     *
     * @param string $gate The gate (e.g. GitHub)
     * @param string $door The door (e.g. Nasqueron)
     * @return Service|null The service information is found; otherwise, null.
     */
    public function findServiceByDoor (string $gate, string $door) : ?Service {
        foreach ($this->services as $service) {
            if ($service->gate === $gate && $service->door === $door) {
                return $service;
            }
        }

        return null;
    }

    /**
     * Finds a service for a specific gate, property and value
     *
     * @param string $gate The gate (e.g. Phabricator)
     * @param string $property The property to check (e.g. instance)
     * @param mixed $value The property value to find
     *                     (e.g. 'http://devcentral.nasqueron.org')
     * @return Service|null The service information is found; otherwise, null.
     */
    public function findServiceByProperty (
        string $gate,
        string $property,
        mixed $value
    ) : ?Service {
        foreach ($this->services as $service) {
            if ($service->gate === $gate && $service->$property === $value) {
                return $service;
            }
        }

        return null;
    }
}
