<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * PingEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#pullrequestevent
 */
class PullRequestEvent extends Event {

    /**
     * Determines if the action is valid.
     *
     * @param string $action The action to check
     * @return bool true if the action is valid; otherwise, false
     */
    protected static function isValidAction(string $action) : bool {
        $actions = [
            'assigned', 'unassigned',
            'labeled', 'unlabeled',
            'opened',  'closed',
            'edited', 'reopened',
        ];
        return in_array($action, $actions);
    }

    /**
     * Gets description for the payload.
     */
    public function getDescription () : string {
        $action = $this->payload->action;

        if (!static::isValidAction($action)) {
            return trans(
                'GitHub.EventsDescriptions.PullRequestEventUnknown',
                ['action' => $action]
            );
        }

        $key  = 'GitHub.EventsDescriptions.PullRequestEventPerAction.';
        $key .=  $action;

        return trans($key, $this->getLocalisationParameters());
    }

    /**
     * Gets the parameters to pass to the localisation message
     */
    private function getLocalisationParameters () : array {
        $parameters = [
            'author'  => $this->payload->sender->login,
            'number'  => $this->payload->number,
            'title'  => $this->payload->pull_request->title,
        ];
        switch ($this->payload->action) {
            case 'assigned':
                $parameters['assignee'] = $this->getLastAssignee();
                break;
        }
        return $parameters;
    }

    /**
     * @return string The last assignee username, or "" if there is no assignee.
     */
    private function getLastAssignee() : string {
        $assignees = $this->payload->pull_request->assignees;

        if (count($assignees) === 0) {
            return ""; // No assignee.
        }

        $assignee = array_pop($assignees);
        return $assignee->login;
    }

    /**
     * Gets link for the payload.
     */
    public function getLink () : string {
        return $this->payload->pull_request->html_url;
    }

}
