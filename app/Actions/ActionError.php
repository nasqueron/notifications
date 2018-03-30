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

    public function __construct (\Exception $ex) {
        $this->type = class_basename(get_class($ex));
        $this->message = $ex->getMessage();
    }
}
