<?php

namespace Nasqueron\Notifications;

use Storage;

class Services {

    ///
    /// Methods to get a list of services
    ///

    /**
     * Gets the services found in credentials.json
     *
     * @return array
     */
    public static function get () {
        $path = config('services.gate.credentials');
        $data = json_decode(Storage::get($path));
        return $data->services;
    }

    /**
     * Gets all the services for a specific gate
     *
     * @param string $gate The gate (e.g. GitHub)
     * @return array
     */
    public static function getForGate ($gate) {
        $services = [];

        foreach (self::get() as $service) {
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
     * @return stdClass|null The service information is found; otherwise, null.
     */
    public static function findServiceByDoor ($gate, $door) {
        foreach (self::get() as $service) {
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
     * @return stdClass|null The service information is found; otherwise, null.
     */
    public static function findServiceByProperty ($gate, $property, $value) {
        foreach (Services::get() as $service) {
            if ($service->gate === $gate && $service->$property === $value) {
                return $service;
            }
        }

        return null;
    }
}
