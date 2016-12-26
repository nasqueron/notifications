<?php

namespace Nasqueron\Notifications\Services;

use Storage;

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
     * Initializes a new instance of the Services class deserializing a JSON file.
     *
     * @param $file the JSON file to deserialize
     * @return Services the deserialized instance
     */
    public static function loadFromJson ($file) {
        $data = json_decode(Storage::get($file));
        $mapper = new \JsonMapper();

        return $mapper->map($data, new self());
    }

    ///
    /// Methods to get a list of services
    ///

    /**
     * Gets the services found in credentials.json
     *
     * @return array
     */
    public function get () {
        return $this->services;
    }

    /**
     * Gets all the services for a specific gate
     *
     * @param string $gate The gate (e.g. GitHub)
     * @return array
     */
    public function getForGate ($gate) {
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
     * @return \stdClass|null The service information is found; otherwise, null.
     */
    public function findServiceByDoor ($gate, $door) {
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
     * @param mixed $value The property value to find (e.g. 'http://devcentral.nasqueron.org')
     * @return \stdClass|null The service information is found; otherwise, null.
     */
    public function findServiceByProperty ($gate, $property, $value) {
        foreach ($this->services as $service) {
            if ($service->gate === $gate && $service->$property === $value) {
                return $service;
            }
        }

        return null;
    }
}
