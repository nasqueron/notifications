<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Event;
use Log;
use Request;

use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

//use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;

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
            return;
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
        $this->payload = $_POST;
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

    /**
     * Logs the request
     */
    protected function logRequest () {
        Log::info('[Gate] New payload.', [
            'service' => static::SERVICE_NAME,
            'door' => $this->door
        ]);
        file_put_contents(
            storage_path('logs/payload.json'),
            json_encode($this->payload)
        );
    }

    ///
    /// Payload processing
    ///

    /**
     * Gets story from the request
     *
     * @return PhabricatorStory
     */
    protected function getStory () {
        return PhabricatorStory::loadFromArray(
            $this->instance,
            $this->payload
        );
    }

    protected function onPayload () {
        $this->initializeReport();

        Event::fire(new PhabricatorPayloadEvent(
            $this->door,
            $this->getStory()
        ));
    }
}
