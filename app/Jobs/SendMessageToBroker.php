<?php

namespace Nasqueron\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Keruald\Brokers\AMQPBroker;
use Nasqueron\Notifications\Jobs\Job;

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
        $this->getBroker()
            ->setExchangeTarget("github_events")
            ->routeTo($this->routingKey)
            ->sendMessage($this->message);
    }

    /**
     * Gets broker
     *
     * @return Broker
     * @todo Transform into a service provider, a singleton pattern and a facade
     */
    public function getBroker() {
        $broker = new AMQPBroker();
        $broker->connect(
            Config::get('broker.connections.amqp.host'),
            Config::get('broker.connections.amqp.port'),
            Config::get('broker.connections.amqp.username'),
            Config::get('broker.connections.amqp.password'),
            Config::get('broker.connections.amqp.vhost')
        );
        return $broker;
    }
}

