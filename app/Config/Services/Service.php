<?php

namespace Nasqueron\Notifications\Config\Services;

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
    public function getInstanceName () : string {
        if (!isset($this->instance)) {
            return "ø";
        }

        return $this->instance;
    }
}
