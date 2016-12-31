<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;

use Symfony\Component\HttpFoundation\Response;

use Event;
use Request;

class PhabricatorGateController extends GateController {

    ///
    /// Private members
    ///

    /**
     * The request content, as a structured data
     *
     * @var array
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onPost ($door) : Response {
        $this->door = $door;

        if (!$this->doesServiceExist()) {
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

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        $this->initializeReport();

        Event::fire(new PhabricatorPayloadEvent(
            $this->door,
            $this->payload
        ));
    }
}
