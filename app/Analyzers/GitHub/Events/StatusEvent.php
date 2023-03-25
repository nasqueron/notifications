<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * StatusEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#statusevent
 */
class StatusEvent extends Event {

    /**
     * Gets state localized message
     */
    private function getState () : string {
        $state = $this->payload->state; // pending, success, failure, or error
        $key = 'GitHub.StatusEventState.' . $state;
        return trans($key);
    }

    /**
     * Gets status result
     */
    private function getStatusResult () : string {
        $glue = trans('GitHub.Separator');
        $fragments = array_filter([
            $this->payload->context,
            $this->payload->description,
            $this->getState(),
        ]);

        return implode($glue, $fragments);
    }

    /**
     * Gets description for the payload
     */
    public function getDescription () : string {
        return trans('GitHub.EventsDescriptions.StatusEvent', [
            'commit' => substr($this->payload->sha, 0, 8),
            'status' => $this->getStatusResult(),
        ]);
    }

    /**
     * Gets link for the payload
     */
    public function getLink () : string {
        $url = $this->payload->target_url;

        if ($url === null) {
            return "";
        }

        return $url;
    }
}
