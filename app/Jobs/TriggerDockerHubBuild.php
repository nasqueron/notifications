<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\TriggerDockerHubBuildAction;
use Nasqueron\Notifications\Events\ReportEvent;
use Nasqueron\Notifications\Facades\DockerHub;

use Exception;
use Illuminate\Support\Facades\Event;

/**
 * This class allows to trigger a new Docker Hub build.
 */
class TriggerDockerHubBuild extends Job {

    ///
    /// Private members
    ///

    /**
     * @var string The Docker image to trigger a build for
     */
    private $image;

    /**
     * @var TriggerDockerHubBuildAction
     */
    private $actionToReport;

    ///
    /// Constructor
    ///

    /**
     * Initializes a new instance of TriggerDockerHubBuild.
     */
    public function __construct ($image) {
        $this->image = $image;
    }

    ///
    /// Task
    ///

    /**
     * Executes the job.
     */
    public function handle () : void {
        $this->initializeReport();
        $this->triggerBuild();
        $this->sendReport();
    }

    /**
     * Initializes the actions report.
     */
    private function initializeReport () : void {
        $this->actionToReport = new TriggerDockerHubBuildAction($this->image);
    }

    /**
     * Triggers a new Docker Hub build.
     */
    private function triggerBuild () : void {
        try {
            DockerHub::build($this->image);
        } catch (Exception $ex) {
            $actionError = new ActionError($ex);
            $this->actionToReport->attachError($actionError);
        }
    }

    /**
     * Fires a report event with the actions report.
     */
    private function sendReport () : void {
        $event = new ReportEvent($this->actionToReport);
        Event::dispatch($event);
    }

}
