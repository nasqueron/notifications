<?php

namespace Nasqueron\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Keruald\Mailgun\MailgunMessageFactory
 */
class Mailgun extends Facade {

    /**
     * Gets the registered name of the component.
     */
    protected static function getFacadeAccessor() : string {
        return 'mailgun';
    }

}
