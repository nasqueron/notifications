<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Events\JenkinsPayloadEvent;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class JenkinsGateController extends GateController {

    ///
    /// Private members
    ///

    /**
     * The request content, as a structured data
     *
     * @var \stdClass
     */
    private $payload;

    /**
     * The request content
     *
     * @var string
     */
    private $rawRequestContent;

    ///
    /// Request processing
    ///

    /**
     * Handles POST requests
     *
     * @param string $door The door, matching the project for this payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onPost (string $door) : Response {
        // Parses the request and check if it's legit

        $this->door = $door;
        $this->extractPayload();

        // Process the request

        $this->logRequest();
        $this->onPayload();

        // Output

        return parent::renderReport();
    }

    /**
     * Extracts payload from the request
     */
    protected function extractPayload () {
        $request = Request::instance();
        $this->rawRequestContent = $request->getContent();
        $this->payload = json_decode($this->rawRequestContent);
    }

    public function getServiceName () : string {
        return "Jenkins";
    }

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        $this->initializeReport();

        Event::dispatch(new JenkinsPayloadEvent(
            $this->door,
            $this->payload
        ));
    }
}
