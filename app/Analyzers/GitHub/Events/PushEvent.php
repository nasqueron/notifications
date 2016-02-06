<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * PushEvent payload analyzer
 *
 * @link https://developer.github.com/v3/activity/events/types/#pushevent
 */
class PushEvent extends Event {

    use WithCommit;
    use WithRepoAndBranch;

    /**
     * Gets the description message key according the amount of commits
     *
     * @param int $count The count of commits
     * @return The l10n message key for description
     */
    private static function getDescriptionMessageKey ($count) {
        $key = 'GitHub.EventsDescriptions.PushEvent';

        if ($count === 0) {
            return $key . '.0';
        }

        return $key . '.n';
    }

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () {
        $n = count($this->payload->commits);

        if ($n === 1) {
            // If only one commit is pushed at the time,
            // we want a description for this commit.
            return $this->getHeadCommitDescription();
        }

        // Otherwise, we want a description for the push.
        return trans(self::getDescriptionMessageKey($n), [
            'user' => $this->payload->pusher->name,
            'count' => $n,
            'repoAndBranch' => $this->getWhere(),
        ]);
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () {
        $n = count($this->payload->commits);

        if ($n === 1) {
            return $this->payload->head_commit->url;
        }

        return $this->payload->compare;
    }
}
