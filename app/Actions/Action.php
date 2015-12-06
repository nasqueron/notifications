<?php

namespace Nasqueron\Notifications\Actions;

abstract class Action {
    /**
     * @var string
     */
    public $action;

    /**
     * Initializes a new instance of an action to report
     */
    public function __construct () {
        $this->action = get_called_class();
    }
}
