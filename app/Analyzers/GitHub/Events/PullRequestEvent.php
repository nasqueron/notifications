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
     * @param string $type The action to check
     * @return bool true if the action is valid; otherwise, false
     */
    protected static function isValidAction ($action) {
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
     *
     * @return string
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
     *
     * @return array
     */
    private function getLocalisationParameters () {
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
    private function getLastAssignee() {
        $assignees = $this->payload->pull_request->assignees;

        if (count($assignees) === 0) {
            return ""; // No assignee.
        }

        $assignee = array_pop($assignees);
        return $assignee->login;
    }

    /**
     * Gets link for the payload.
     *
     * @return string
     */
    public function getLink () : string {
        return $this->payload->pull_request->html_url;
    }

}
