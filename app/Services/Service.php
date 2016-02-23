<?php

namespace Nasqueron\Notifications\Services;

class Service {
    /**
     * @var string
     */
    public $gate;

    /**
     * @var string
     */
    public $door;

    /**
     * @var string
     */
    public $instance;

    /**
     * @var string
     */
    public $secret;

    /**
     * Gets instance name
     *
     * @return string The instance name or "ø" if omitted
     */
    public function getInstanceName () {
        if (!isset($this->instance)) {
            return "ø";
        }

        return $this->instance;
    }
}
