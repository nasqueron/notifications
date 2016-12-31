<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * WatchEvent payload analyzer
 *
 * Triggered when an user stars a repository.
 *
 * The current starring action on GitHub is the old watch action. To avoid
 * to break code, former event name have been kept in the API.
 *
 * @link https://developer.github.com/changes/2012-09-05-watcher-api/
 * @link https://developer.github.com/v3/activity/events/types/#watchevent
 */
class WatchEvent extends Event {

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () : string {
        return trans(
            'GitHub.EventsDescriptions.WatchEvent',
            [
                'user' => $this->payload->sender->login,
                'repository' => $this->payload->repository->full_name,
            ]
        );
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () : string {
        return $this->payload->sender->html_url;
    }
}
