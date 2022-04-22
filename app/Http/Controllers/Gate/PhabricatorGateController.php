<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

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
    /// Requests processing
    ///

    /**
     * Handles POST requests
     *
     * @param string $door The door, matching the project for this payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onPost (string $door) : Response {
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
    protected function extractPayload () : void {
        $this->payload = Request::all();
    }

    public function getServiceName () : string {
        return "Phabricator";
    }

    ///
    /// Payload processing
    ///

    protected function onPayload () : void {
        $this->initializeReport();

        Event::dispatch(new PhabricatorPayloadEvent(
            $this->door,
            $this->payload
        ));
    }
}
