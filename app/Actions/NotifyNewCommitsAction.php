<?php

namespace Nasqueron\Notifications\Actions;

class NotifyNewCommitsAction extends Action {
    /**
     * The Phabricator repository call sign
     *
     * @var string
     */
    public $callSign;

    /**
     * Initializes a new instance of a AMQP action to report.
     *
     * @param string $callSign The Phabricator repository call sign
     */
    public function __construct (string $callSign) {
        parent::__construct();

        $this->callSign = $callSign;
    }
}
