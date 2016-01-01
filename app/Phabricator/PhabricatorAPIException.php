<?php

namespace Nasqueron\Notifications\Phabricator;

class PhabricatorAPIException extends \RuntimeException {
    /**
     * @param int $code The error_code field for the API reply
     * @param string $message The error_info field from the API reply
     */
    public function __construct ($code, $message) {
        $this->code = $code;
        $this->message = $message;
    }
}
