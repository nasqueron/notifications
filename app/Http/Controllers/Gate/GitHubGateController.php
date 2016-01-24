<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Event;
use Request;

use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Keruald\GitHub\XHubSignature;

class GitHubGateController extends GateController {

    ///
    /// Private members
    ///

    /**
     * The request signature, allowing to determine if the payload is legit
     *
     * @var string
     */
    private $signature;

    /**
     * The GitHub event triggering this request
     *
     * @var string
     */
    private $event;

    /**
     * The request delivery GUID
     *
     * @var string
     */
    private $delivery;

    /**
     * The request content, as a structured data
     *
     * @var stdClass
     */
    private $payload;

    /**
     * The request content
     *
     * @var string
     */
    private $rawRequestContent;

    ///
    /// Constants
    ///

    const SERVICE_NAME = 'GitHub';

    ///
    /// Request processing
    ///

    /**
     * Handles POST requests
     *
     * @param Request $request the HTTP request
     * @return Illuminate\Http\Response
     */
    public function onPost ($door) {
        // Parses the request and check if it's legit

        $this->door = $door;
        $this->extractHeaders();
        $this->extractPayload();

        if (!$this->isLegitRequest()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$this->isValidRequest()) {
            abort(400, 'Bad request.');
        }

        // Process the request

        $this->logGateRequest();
        $this->onPayload();

        // Output

        return parent::renderReport();
    }

    /**
     * Extracts headers from the request
     */
    protected function extractHeaders () {
        $this->signature = $this->getSignature();
        $this->event     = Request::header('X-Github-Event');
        $this->delivery  = Request::header('X-Github-Delivery');
    }

    /**
     * Gets the signature from an X-Hub-Signature header
     *
     * @param string the signature part of the header
     */
    private function getSignature () {
        $headerSignature = Request::header('X-Hub-Signature');
        return XHubSignature::parseSignature($headerSignature);
    }

    /**
     * Extracts payload from the request
     */
    protected function extractPayload () {
        $request = Request::instance();
        $this->rawRequestContent = $request->getContent();
        $this->payload = json_decode($this->rawRequestContent);
    }

    /**
     * Determines if the request is valid, ie contains the mandatory headers
     * and a payload.
     *
     * @return bool true if the request looks valid; otherwise, false.
     */
    protected function isValidRequest () {
        if (empty($this->event)) {
            return false;
        }
        if (empty($this->delivery)) {
            return false;
        }
        if (empty($this->payload) || !is_object($this->payload)) {
            return false;
        }
        return true;
    }

    /**
     * Determines if the request is legit.
     *
     * @return bool true if the request looks legit; otherwise, false.
     */
    protected function isLegitRequest () {
        $secret = $this->getSecret();

        // If the secret is not defined, request legitimation is bypassed
        if (empty($secret)) {
            return true;
        }

        // If the secret is defined, but signature is missing from the
        // request, we don't need to perform any other validation.
        if (empty($this->signature)) {
            return false;
        }

        return XHubSignature::validatePayload(
            $secret,
            $this->rawRequestContent,
            $this->signature
        );
    }

    /**
     * Logs the request
     */
    protected function logGateRequest () {
        $this->logRequest([
            'delivery' => $this->delivery,
            'event' => $this->event,
        ]);
    }

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        $this->initializeReport();

        Event::fire(new GitHubPayloadEvent(
            $this->door,
            $this->event,
            $this->payload
        ));
    }
}
