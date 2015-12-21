<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Features;
use Nasqueron\Notifications\Http\Controllers\Controller;

use Report;

/**
 * Represents a controller handling an entry-point for API payloads
 */
class GateController extends Controller {

    ///
    /// Private members
    ///

    /**
     * @var string
     */
    protected $door;

    ///
    /// Requests
    ///

    /**
     * Handles GET requests
     */
    public function onGet ($door = null) {
        // Virtually all the push APIs will send they payloads
        // using a POST request, so we can provide a sensible
        // default GET error message.
        return view('gate/ispostonly');
    }

    /**
     * Logs the request
     */
    protected function logRequest () {
        Log::info('[Gate] New payload.', [
            'service' => static::SERVICE_NAME,
            'door' => $this->door,
        ]);
    }

    ///
    /// Reports
    ///

    /**
     * Initializes the report and registers it
     */
    protected function initializeReport () {
        if (Features::isEnabled('ActionsReport')) {
            Report::attachToGate(static::SERVICE_NAME, $this->door);
        }
    }

    /**
     * Renders the report
     *
     * @return Illuminate\Http\Response|null
     */
    protected function renderReport () {
        if (Features::isEnabled('ActionsReport')) {
            return Report::render();
        }
    }

    ///
    /// Credentials
    ///

    /**
     * Gets services credentials
     *
     * @return stdClass the services credentials
     */
    protected function getServicesCredentials () {
        $path = config('services.gate.credentials');
        $data = json_decode(file_get_contents($path));
        return $data->services;
    }

    /**
     * Determines if a service definition matches this current gate and door.
     *
     * @param stdClass $service the service to check
     * @return true if the service matches our gate and door; otherwise, false.
     */
    protected function doesServiceMatch ($service) {
        return $service->gate == static::SERVICE_NAME
            && $service->door == $this->door;
    }

    /**
     * Gets service credentials for this gate and door
     *
     * @return stdClass the service credentials
     */
    public function getService () {
        foreach ($this->getServicesCredentials() as $service) {
            if ($this->doesServiceMatch($service)) {
                return $service;
            }
        }

        return null;
    }

    /**
     * Gets secret for this service and door.
     *
     * @return string the secret, or if unknown, an empty string
     */
    protected function getSecret () {
        $service= $this->getService();

        if ($service !== null) {
            return $service->secret;
        }

        return "";
    }

}
