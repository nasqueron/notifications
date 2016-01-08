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
     * Attaches an error to the action to report
     *
     * To attach an exception, you can use:
     * <code>
     * $actionToReport->attachError(new ActionError($exception));
     * </code>
     *
     * @param Nasqueron\Notifications\Actions\ActionError $error The error to attach
     */
    public function attachError (ActionError $error) {
        $this->error = $error;
    }
}
