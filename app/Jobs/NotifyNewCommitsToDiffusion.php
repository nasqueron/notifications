<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\NotifyNewCommitsAction;
use Nasqueron\Notifications\Events\ReportEvent;
use Nasqueron\Notifications\Phabricator\PhabricatorAPI as API;
use Nasqueron\Notifications\Phabricator\PhabricatorAPIException;


use Event;
use Log;
use PhabricatorAPI;

/**
 * This class allows to notify Phabricator of new commits, so daemons can pull
 * these new commits and add them into Diffusion.
 */
class NotifyNewCommitsToDiffusion extends Job {

    ///
    /// Private members
    ///

    /**
     * The clone URL of the repository
     *
     * @var string
     */
    private $repository;

    /**
     * @var \Nasqueron\Notifications\Phabricator\PhabricatorAPI
     */
    private $api;

    /**
     * @var string
     */
    private $callSign;

    /**
     * @var NotifyNewCommitsAction
     */
    private $actionToReport;

    /**
     * @var string
     */
    private $sourceProject;

    ///
    /// Constructor
    ///

    /**
     * Initializes a new instance of NotifyNewCommitsToDiffusion.
     */
    public function __construct ($sourceProject, $repository) {
        $this->sourceProject = $sourceProject;
        $this->repository = $repository;
    }

    ///
    /// Task
    ///

    /**
     * Executes the job.
     *
     * @return void
     */
    public function handle () : void {
        if (!$this->fetchRequirements()) {
            return;
        }

        $this->initializeReport();
        $this->notifyPhabricator();
        $this->sendReport();
    }

    /**
     * Initializes the actions report.
     */
    private function initializeReport () : void {
        $this->actionToReport = new NotifyNewCommitsAction($this->callSign);
    }

    /**
     * Notifies Phabricator to pull from the repository.
     */
    private function notifyPhabricator () : void {
        try {
            $this->callDiffusionLookSoon();
        } catch (PhabricatorAPIException $ex) {
            $actionError = new ActionError($ex);
            $this->actionToReport->attachError($actionError);
            Log::error($ex);
        }
    }

    /**
     * Fires a report event with the actions report.
     */
    private function sendReport () : void {
        $event = new ReportEvent($this->actionToReport);
        Event::fire($event);
    }


    ///
    /// Helper methods to find correct Phabricator instance and get the API
    ///

    /**
     * Gets the relevant Phabricator project for the specified source project.
     *
     * @return string The Phabricator project name
     */
    private function getPhabricatorProject () : string {
        return $this->sourceProject;
    }

    ///
    /// Helper methods to populate object members
    ///

    /**
     * Fetches API and call sign.
     *
     * @return bool true if all requirement have been fetched ; otherwise, false.
     */
    private function fetchRequirements () : bool {
        return $this->fetchAPI() && $this->fetchCallSign();
    }

    /**
     * Fetches the Phabricator API to use for the current source project.
     *
     * @return bool true if an API instance has been fetch ; otherwise, false.
     */
    private function fetchAPI () : bool {
        $project = $this->getPhabricatorProject();
        $this->api = PhabricatorAPI::getForProject($project);

        return $this->api !== null;
    }

    /**
     * Fetches the call sign matching the repository.
     *
     * @return bool true if a call sign have been found ; otherwise, false.
     */
    private function fetchCallSign () : bool {
        $this->callSign = $this->getCallSign();

        return $this->callSign !== "";
    }

    ///
    /// Helper methods to query Phabricator API
    ///

    /**
     * Gets the call sign matching the repository URL.
     *
     * @return string the repository call sign "OPS", or "" if not in Phabricator
     */
    private function getCallSign () : string {
        $reply = $this->api->call(
            'repository.query',
            [ 'remoteURIs[0]' => $this->repository ]
        );

        if (!count($reply)) {
            return "";
        }

        return API::getFirstResult($reply)->callsign;
    }

    /**
     * Calls the diffusion.looksoon API method.
     *
     * @throws PhabricatorAPIException
     */
    private function callDiffusionLookSoon () : void {
        $this->api->call(
            'diffusion.looksoon',
            [ 'callsigns[0]' => $this->callSign ]
        );
    }

}
