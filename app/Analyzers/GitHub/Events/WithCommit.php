<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * Helper methods for events with a need to specify commit information
 * (e.g. push)
 *
 * @link https://developer.github.com/v3/activity/events/types/#pushevent
 */
trait WithCommit {

    /**
     * Gets the title of the head commit
     *
     * @return string
     */
    private function getHeadCommitTitle () {
        return static::getCommitTitle($this->payload->head_commit->message);
    }

    /**
     * Extracts the commit title from the whole commit message.
     *
     * @param string $message The commit message
     * @return string The commit title
     */
    public static function getCommitTitle ($message) {
        // Discards extra lines
        $pos = strpos($message, "\n");
        if ($pos > 0) {
            $message = substr($message, 0, $pos);
        }

        // Short messages are returned as is
        // Longer messages are truncated
        return self::cut($message, 72);
    }

    /**
     * Gets the description text for the head commit.
     *
     * @return string
     */
    private function getHeadCommitDescription () {
        $commit = $this->payload->head_commit;
        $committer = $commit->committer->username;
        $author = $commit->author->username;

        $message = trans('GitHub.Commits.Message', [
            'committer' => $committer,
            'title' => $this->getHeadCommitTitle(),
        ]);
        
        if ($committer !== $author) {
            $message .= trans('GitHub.Commits.Authored', [
                'author' => $author,
            ]);
        }

        return $message;
    }

}
