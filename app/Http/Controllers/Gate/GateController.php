<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Actions\ActionsReport;
use Nasqueron\Notifications\Config\Features;
use Nasqueron\Notifications\Config\Services\Service;
use Nasqueron\Notifications\Facades\Services;
use Nasqueron\Notifications\Facades\Report;
use Nasqueron\Notifications\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Represents a controller handling an entry-point for API payloads
 */
abstract class GateController extends Controller {

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
    public function onGet () : View {
        // Virtually all the push APIs will send they payloads
        // using a POST request, so we can provide a sensible
        // default GET error message.
        return view('gate/ispostonly');
    }

    /**
     * Logs the request
     */
    protected function logRequest (array $extraContextualData = []) : void {
        Log::info('[Gate] New payload.', [
            'service' => $this->getServiceName(),
            'door' => $this->door,
        ] + $extraContextualData);
    }

    ///
    /// Reports
    ///

    /**
     * Initializes the report and registers it
     */
    protected function initializeReport () : void {
        if (Features::isEnabled('ActionsReport')) {
            Report::attachToGate($this->getServiceName(), $this->door);
        }
    }

    /**
     * Renders the report
     */
    protected function renderReport () : BaseResponse {
        if (!Features::isEnabled('ActionsReport')) {
            return response("");
        }

        $report = App::make('report');
        $statusCode = $report->containsError() ? 503 : 200;

        $this->logResponse($report, $statusCode);

        return Response::json($report)
            ->setStatusCode($statusCode);
    }

    /**
     * Logs the action reports sent as response by the controller
     */
    protected function logResponse (ActionsReport $report, int $statusCode) : void {
        Log::info("[Gate] Actions report.", $report->toArray());
        Log::info("[Gate] Response is HTTP $statusCode");
    }

    ///
    /// Credentials
    ///

    abstract public function getServiceName ();

    /**
     * Gets service credentials for this gate and door
     */
    public function getService () : ?Service {
        return Services::findServiceByDoor(
            $this->getServiceName(),
            $this->door
        );
    }

    /**
     * Checks if a registered service exists for this service and door.
     */
    protected function doesServiceExist () : bool {
        return $this->getService() !== null;
    }

    /**
     * Gets secret for this service and door.
     *
     * @return string the secret, or if unknown, an empty string
     */
    protected function getSecret () : string {
        $service= $this->getService();

        if ($service !== null) {
            return $service->secret;
        }

        return "";
    }

}
