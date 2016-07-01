<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Event;
use Request;

use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;

class PhabricatorGateController extends GateController {

    ///
    /// Private members
    ///

    /**
     * The request content, as a structured data
     *
     * @var string
     */
    private $payload;

    ///
    /// Constants
    ///

    /**
     * The name of the service this gate accepts payload from.
     */
    const SERVICE_NAME = 'Phabricator';

    ///
    /// Requests processing
    ///

    /**
     * Handles POST requests
     *
     * @param Request $request the HTTP request
     */
    public function onPost ($door) {
        $this->door = $door;
        $this->instance = $this->getInstance();

        if ($this->instance === "") {
            abort(404, 'Unknown Phabricator instance.');
        }

        $this->extractPayload();

        $this->logRequest();
        $this->onPayload();

        return parent::renderReport();
    }

    /**
     * Extracts payload from the request
     */
    protected function extractPayload () {
        $this->payload = Request::all();
    }

    /**
     * Gets the instance matching this door
     *
     * @return string The Phabricator root URL without trailing slash
     */
     protected function getInstance () {
         $service = $this->getService();
         if ($service === null) {
            return "";
         }

         return $service->instance;
     }

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        $this->initializeReport();

        Event::fire(new PhabricatorPayloadEvent(
            $this->door,
            $this->instance,
            $this->payload
        ));
    }
}
