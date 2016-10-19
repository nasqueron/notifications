<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * IssueCommentEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#issuecommentevent
 */
class IssueCommentEvent extends Event {

    /**
     * Determines if the action is valid.
     *
     * @param string $type The action to check
     * @return bool true if the action is valid; otherwise, false
     */
    protected static function isValidAction ($action) {
        $actions = ['created', 'edited', 'deleted'];
        return in_array($action, $actions);
    }

    /**
     * Gets description for the payload.
     *
     * @return string
     */
    public function getDescription () {
        $action = $this->payload->action;

        if (!static::isValidAction($action)) {
            return trans(
                'GitHub.EventsDescriptions.IssueCommentEventUnknown',
                ['action' => $action]
            );
        }

        $key  = 'GitHub.EventsDescriptions.IssueCommentEventPerAction.';
        $key .=  $action;

        $comment = $this->payload->comment;
        $issue = $this->payload->issue;

        return trans(
            $key,
            [
                'author'  => $comment->user->login,
                'issueNumber'  => $issue->number,
                'issueTitle'  => $issue->title,
                'excerpt' => self::cut($comment->body),
            ]
        );
    }

    /**
     * Gets link for the payload.
     *
     * @return string
     */
    public function getLink () {
        return $this->payload->comment->html_url;
    }

}

