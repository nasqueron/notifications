<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\TriggerDockerHubBuildAction;
use Nasqueron\Notifications\Events\ReportEvent;

use DockerHub;
use Event;

use BadMethodCallException;
use Exception;

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
     *
     * @return void
     */
    public function handle () {
        $this->initializeReport();
        $this->triggerBuild();
        $this->sendReport();
    }

    /**
     * Initializes the actions report.
     */
    private function initializeReport () {
        $this->actionToReport = new TriggerDockerHubBuildAction($this->image);
    }

    /**
     * Triggers a new Docker Hub build.
     */
    private function triggerBuild () {
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
    private function sendReport () {
        $event = new ReportEvent($this->actionToReport);
        Event::fire($event);
    }

}
