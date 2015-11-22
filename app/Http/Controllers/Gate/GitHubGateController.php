<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Log;
use Request;

use Keruald\GitHub\XHubSignature;

class GitHubGateController extends GateController {

    ///
    /// Private members
    ///

    private $signature;
    private $event;
    private $delivery;
    private $payload;

    ///
    /// Constants
    ///

    const SERVICE_NAME = 'GitHub';

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
        $this->extractHeaders();
        $this->extractPayload();

        if (!$this->isLegitRequest()) {
            abort(403, 'Unauthorized action.');
            return;
        }

        $this->logRequest();
        $this->onPayload();
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
        $this->payload = $request->getContent();
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

        return XHubSignature::validatePayload(
            $secret,
            $this->payload,
            $this->signature
        );
    }

    /**
     * Logs the request
     */
    protected function logRequest () {
        Log::info('[Gate] New payload.', [
            'service' => static::SERVICE_NAME,
            'door' => $this->door,
            'delivery' => $this->delivery,
            'event' => $this->event,
        ]);
    }

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        // Here the logic to process the event.
    }
}
