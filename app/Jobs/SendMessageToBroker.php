<?php

namespace Nasqueron\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Nasqueron\Notifications\Jobs\Job;

use Broker;

class SendMessageToBroker extends Job implements SelfHandling {

    ///
    /// Private members
    ///

    /**
     * The routing key, for topic exchange
     *
     * @var string
     */
    private $routingKey = '';

    /**
     * The message to send
     *
     * @var string
     */
    private $message = '';

    /**
     * The target exchange
     *
     * @var string
     */
    private $target = 'github_events';

    ///
    /// Constructor
    ///

    /**
     * Create a new job instance.
     *
     * @param $routingKey the routing key, for topic exchange
     * @param $message the message to send
     *
     * @return void
     */
    public function __construct ($routingKey, $message) {
        $this->routingKey = $routingKey;
        $this->message = $message;
    }

    ///
    /// Task
    ///

    /**
     * Executes the job.
     *
     * @return void
     */
    public function handle() {
        $this->sendMessage();
    }

    /**
     * Sends the message to the broker
     */
    protected function sendMessage () {
        Broker::setExchangeTarget($this->target)
            ->routeTo($this->routingKey)
            ->sendMessage($this->message);
    }
}
