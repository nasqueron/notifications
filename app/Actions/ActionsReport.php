<?php

namespace Nasqueron\Notifications\Actions;

use Nasqueron\Notifications\Events\ReportEvent;

use Event;
use Response;

class ActionsReport {
    /**
     * List of actions
     *
     * @var Action[]
     */
    public $actions = [];

    /**
     * Report created date
     *
     * @var int
     */
    public $created;

    /**
     * The entry gate
     *
     * @var string
     */
    public $gate;

    /**
     * The entry door
     *
     * @var string
     */
    public $door;

    /**
     * Initializes a new instance of an actions report
     */
    public function __construct () {
        $this->created = time();

        $this->listenToReports();
    }

    ///
    /// Properties
    ///

    /**
     * Sets the gate and the door for this report
     *
     * @param string $gate The gate
     * @param string $door The door
     */
    public function attachToGate ($gate, $door) {
        $this->gate = $gate;
        $this->door = $door;
    }

    /**
     * Adds an action to the list of actions to report
     *
     * @param Action $action The action to add
     */
    public function addAction (Action $action) {
        $this->actions[] = $action;
    }

    /**
     * Determines if one of the action has failed.
     *
     * @return bool
     */
    public function containsError () {
        foreach ($this->actions as $action) {
            if ($action->error !== null) {
                return true;
            }
        }

        return false;
    }

    ///
    /// Output
    ///

    /**
     * Gets a JSON string representation of the current instance
     */
    public function __toString () {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    /**
     * Renders the report
     *
     * @return Illuminate\Http\Response
     */
    public function render () {
        return Response::json($this)
            ->setStatusCode($this->getResponseStatusCode());
    }

    /**
     * Determines the HTTP status code of the response
     *
     * @return int 200 if all is fine, 503 if an error is present
     */
    public function getResponseStatusCode () {
        return $this->containsError() ? 503 : 200;
    }

    ///
    /// Events
    ///

    /**
     * Listens to actions to report
     */
    public function listenToReports () {
        Event::listen(
            'Nasqueron\Notifications\Events\ReportEvent',
            function (ReportEvent $event) {
                $this->addAction($event->action);
            }
        );
    }
}
