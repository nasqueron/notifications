<?php

namespace Nasqueron\Notifications\Actions;

class ActionError {

    /**
     * Exception type
     *
     * @var string
     */
    public $type;

    /**
     * Exception message
     *
     * @var string
     */
    public $message;

    /**
     * Initializes a new action error report instance from the specified Exception
     *
     * @param Exception $ex The exception to build an ActionError instance from
     */
    public function __construct (\Exception $ex) {
        $this->type = class_basename(get_class($ex));
        $this->message = $ex->getMessage();
    }
}
