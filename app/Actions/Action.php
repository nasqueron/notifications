<?php

namespace Nasqueron\Notifications\Actions;

abstract class Action {
    /**
     * @var string
     */
    public $action;

    /**
     * @var Nasqueron\Notifications\Actions\ActionError
     */
    public $error;

    /**
     * Initializes a new instance of an action to report
     */
    public function __construct () {
        $this->action = class_basename(get_called_class());
    }

    /**
     * Attaches an exception to an action to report
     *
     * @param \Exception $ex The exception to attach to the error report
     */
    public function attachException ($ex) {
        $this->error = new ActionError($ex);
    }
}
