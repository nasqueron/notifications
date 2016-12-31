<?php

namespace Nasqueron\Notifications\Jobs;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Events\ReportEvent;
use Nasqueron\Notifications\Jobs\Job;

use Broker;
use Event;
use Log;

class SendMessageToBroker extends Job {

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
    private $target = '';

    /**
     * If not null, an exception thrown during the task
     *
     * @var \Exception
     */
    private $exception;

    ///
    /// Constructor
    ///

    /**
     * Creates a new job instance.
     *
     * @param string $target The queue or exchange to send the message to
     * @param string $routingKey The routing key, for topic exchange
     * @param string $message The message to send
     *
     * @return void
     */
    public function __construct (string $target, string $routingKey, string $message) {
        $this->target = $target;
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
    public function handle() : void {
        $this->sendMessage();
        $this->report();
    }

    /**
     * Sends the message to the broker.
     */
    protected function sendMessage () : void {
        try {
            Broker::setExchangeTarget($this->target, "topic", true)
                ->routeTo($this->routingKey)
                ->sendMessage($this->message);
        } catch (\Exception $ex) {
            $this->exception = $ex;
            Log::error($ex);
        }
    }

    /**
     * Prepares a report and fires a report event.
     */
    protected function report () : void {
        $actionToReport = new AMQPAction(
            "publish",
            $this->target,
            $this->routingKey
        );
        if ($this->exception !== null) {
            $actionToReport->attachError(new ActionError($this->exception));
        }
        Event::fire(new ReportEvent($actionToReport));
    }
}
