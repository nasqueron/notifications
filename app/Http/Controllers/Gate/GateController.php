<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Http\Controllers\Controller;

/**
 * Represents a controller handling an entry-point for API payloads
 */
class GateController extends Controller {

    ///
    /// Private members
    ///

    protected $door;

    ///
    /// Requests
    ///

    /**
     * Handles GET requests
     */
    function onGet ($door = null) {
        // Virtually all the push APIs will send they payloads
        // using a POST request, so we can provide a sensible
        // default GET error message.
        return view('gate/ispostonly');
    }

    ///
    /// Credentials
    ///

    /**
     * Gets service credentials
     */
    function getServicesCredentials () {
        $path = config('services.gate.credentials');
        $data = json_decode(file_get_contents($path));
        return $data->services;
    }

    /**
     * Determines if a service definition matches this current gate and door.
     *
     * @param stdClass $service the service to check
     */
    protected function doesServiceMatch ($service) {
        return $service->gate == static::SERVICE_NAME
            && $service->door == $this->door;
    }

    /**
     * Gets secret for this service and door.
     *
     * @return string the secret, or if unknown, an empty string
     */
    protected function getSecret () {
        foreach ($this->getServicesCredentials() as $service) {
            if ($this->doesServiceMatch($service)) {
                return $service->secret;
            }
        }

        return "";
    }
}
