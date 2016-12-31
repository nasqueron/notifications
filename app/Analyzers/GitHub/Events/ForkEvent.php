<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * ForkEvent payload analyzer
 *
 * Triggered when a repository is forked.
 *
 * @link https://developer.github.com/v3/activity/events/types/#forkevent
 */
class ForkEvent extends Event {

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () : string {
        return trans(
            'GitHub.EventsDescriptions.ForkEvent',
            [
                'repo_base' => $this->payload->repository->full_name,
                'repo_fork' => $this->payload->forkee->full_name,
            ]
        );
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () : string {
        return $this->payload->forkee->html_url;
    }
}
