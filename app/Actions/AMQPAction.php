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
     *
     * @param string $method The action done on the broker (e.g. 'publish')
     * @param string $target The queue or exchange target on the broker
     * @param string $routingKey The routing key for this exchange or queue
     */
    public function __construct (string $method, string $target, string $routingKey = '') {
        parent::__construct();

        $this->method = $method;
        $this->target = $target;
        $this->routingKey = $routingKey;
    }
}
