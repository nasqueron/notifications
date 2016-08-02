<?php

namespace Nasqueron\Notifications\Actions;

class AMQPAction extends Action {
    /**
     * The action done on the broker ('publish', 'consume')
     *
     * @var string
     */
    public $method;

    /**
     * The queue or exchange target on the broker
     *
     * @var string
     */
    public $target;

    /**
     * The routing key
     *
     * @var string
     */
    public $routingKey;

    /**
     * Initializes a new instance of a AMQP action to report
     */
    public function __construct ($method, $target, $routingKey = '') {
        parent::__construct();

        $this->method = $method;
        $this->target = $target;
        $this->routingKey = $routingKey;
    }
}
