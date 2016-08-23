<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Features;
use Nasqueron\Notifications\Http\Controllers\Controller;

use App;
use Log;
use Report;
use Response;
use Services;

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
    public function onGet () {
        // Virtually all the push APIs will send they payloads
        // using a POST request, so we can provide a sensible
        // default GET error message.
        return view('gate/ispostonly');
    }

    /**
     * Logs the request
     */
    protected function logRequest ($extraContextualData = []) {
        Log::info('[Gate] New payload.', [
            'service' => static::SERVICE_NAME,
            'door' => $this->door,
        ] + $extraContextualData);
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
            $report = App::make('report');
            $statusCode = $report->containsError() ? 503 : 200;
            return Response::json($report)
                ->setStatusCode($statusCode);
        }
    }

    ///
    /// Credentials
    ///

    /**
     * Gets service credentials for this gate and door
     *
     * @return stdClass the service credentials
     */
    public function getService () {
        return Services::findServiceByDoor(static::SERVICE_NAME, $this->door);
    }

    /**
     * Checks if a registered service exists for this service and door
     */
    protected function doesServiceExist () {
        return $this->getService() !== null;
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
