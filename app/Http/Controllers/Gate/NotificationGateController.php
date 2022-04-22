<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Notifications\Notification;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class NotificationGateController extends GateController {

    ///
    /// Private members
    ///

    /**
     * The request content, as a structured data
     *
     * @var \Nasqueron\Notifications\Notifications\Notification
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

        try {
            $this->extractPayload();
            $this->normalizePayload();
        } catch (InvalidArgumentException $ex) {
            abort(400, 'Bad request.');
        }

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
        $this->payload = $this->getNotification();
    }

    protected function getServiceName () : string {
        return (string)$this->payload->service;
    }

    ///
    /// Helper methods to get notification
    ///

    private function getNotification () : Notification {
        $payload = json_decode($this->rawRequestContent);
        if ($payload === null) {
            throw new InvalidArgumentException("Invalid JSON");
        }

        $mapper = new \JsonMapper();
        return $mapper->map(
            $payload,
            new Notification
        );
    }

    private function normalizePayload () : void {
        $this->normalizeProject();
        $this->ensureRequiredPayloadFieldsArePresent();
    }

    private function normalizeProject () : void {
        if (!$this->isPayloadFieldPresent('project')) {
            $this->payload->project = $this->door;
        }
    }

    private function ensureRequiredPayloadFieldsArePresent () : void {
        foreach ($this->getMandatoryPayloadFields() as $field) {
            if (!$this->isPayloadFieldPresent($field)) {
                throw new InvalidArgumentException("Field $field is missing.");
            }
        }
    }

    private function getMandatoryPayloadFields () : array {
        return [
            'service',
            'project',
            'group',
            'type',
        ];
    }

    private function isPayloadFieldPresent (string $field) : bool {
        return (string)$this->payload->$field !== "";
    }

    ///
    /// Payload processing
    ///

    protected function onPayload () {
        $this->initializeReport();

        Event::dispatch(new NotificationEvent($this->payload));
    }

}
