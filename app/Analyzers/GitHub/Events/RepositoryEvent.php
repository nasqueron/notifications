<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * RepositoryEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#repositoryevent
 */
class RepositoryEvent extends Event {

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () {
        $message = trans('GitHub.EventsDescriptions.RepositoryEvent', [
            'repository' => $this->payload->repository->full_name,
        ]);

        if ($this->payload->repository->fork) {
            $message .= trans('GitHub.EventsDescriptions.RepositoryEventFork');
        }

        if ($description = $this->payload->repository->description) {
            $message .= trans('GitHub.Separator');
            $message .= $description;
        }

        return $message;
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () {
        return $this->payload->repository->html_url;
    }
}
