<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * CommitCommentEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#commitcommentevent
 */
class CommitCommentEvent extends Event {

    /**
     * Gets description for the payload
     */
    public function getDescription () : string {
        $comment = $this->payload->comment;

        return trans(
            'GitHub.EventsDescriptions.CommitCommentEvent',
            [
                'author'  => $comment->user->login,
                'commit'  => substr($comment->commit_id, 0, 8),
                'excerpt' => self::cut($comment->body),
            ]
        );
    }

    /**
     * Gets link for the payload
     */
    public function getLink () : string {
        return $this->payload->comment->html_url;
    }
}
