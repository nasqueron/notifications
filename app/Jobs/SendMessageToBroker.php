<?php

namespace Nasqueron\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Nasqueron\Notifications\Jobs\Job;

use Broker;

class SendMessageToBroker extends Job implements SelfHandling {

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

    /**
     * Executes the job.
     *
     * @return void
     */
    public function handle() {
        Broker::setExchangeTarget("github_events")
            ->routeTo($this->routingKey)
            ->sendMessage($this->message);
    }
}
