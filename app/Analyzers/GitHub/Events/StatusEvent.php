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
     *
     * @return string
     */
    private function getState () {
        $state = $this->payload->state; // pending, success, failure, or error
        $key = 'GitHub.StatusEventState.' . $state;
        return trans($key);
    }

    /**
     * Gets status result
     *
     * @return string
     */
    private function getStatusResult () {
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
     *
     * @return string
     */
    public function getDescription () {
        return trans('GitHub.EventsDescriptions.StatusEvent', [
            'commit' => substr($this->payload->sha, 0, 8),
            'status' => $this->getStatusResult(),
        ]);
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () {
        $url = $this->payload->target_url;

        if ($url === null) {
            return "";
        }

        return $url;
    }
}