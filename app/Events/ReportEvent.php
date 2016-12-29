<?php

namespace Nasqueron\Notifications\Events;

use Nasqueron\Notifications\Actions\Action;
use Nasqueron\Notifications\Events\Event;
use Illuminate\Queue\SerializesModels;

class ReportEvent extends Event {
    use SerializesModels;

    /**
     * @var Action
     */
    public $action;

    /**
     * Creates a new event instance.
     *
     * @param ActionReport $action the action to report
     */
    public function __construct(Action $action) {
        $this->action = $action;
    }
}
