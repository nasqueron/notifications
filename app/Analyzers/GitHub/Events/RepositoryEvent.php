<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * RepositoryEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#repositoryevent
 */
class RepositoryEvent extends Event {

    /**
     * Determines if the action is valid.
     *
     * @param string $action The action to check
     * @return bool true if the action is valid; otherwise, false
     */
    protected static function isValidAction ($action) {
        $actions = ['created', 'deleted', 'publicized', 'privatized'];
        return in_array($action, $actions);
    }

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () : string {
        $action = $this->payload->action;

        if (!static::isValidAction($action)) {
            return trans(
                'GitHub.EventsDescriptions.RepositoryEventUnknown',
                ['action' => $action]
            );
        }

        $key  = 'GitHub.EventsDescriptions.RepositoryEventPerAction.';
        $key .=  $action;

        $repository = $this->payload->repository->full_name;

        $message = trans($key, ['repository' => $repository]);

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
    public function getLink () : string {
        return $this->payload->repository->html_url;
    }
}
