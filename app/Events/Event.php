<?php

namespace Nasqueron\Notifications\Events;

abstract class Event {

    /**
     * The request content, as a structured data
     * @var mixed
     */
    public $payload;

}
