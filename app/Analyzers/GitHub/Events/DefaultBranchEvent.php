<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * DefaultBranchEvent payload analyzer
 *
 * @link https://docs.github.com/en/developers/webhooks-and-events/webhooks/webhook-events-and-payloads#repository
 */
class DefaultBranchEvent extends Event {

    /**
     * Gets description for the payload
     */
    public function getDescription () : string {
        $repository = $this->payload->repository->full_name;
        $old = $this->payload->changes->default_branch->from;
        $new = $this->payload->repository->default_branch;

        return trans(
            'GitHub.EventsDescriptions.DefaultBranchEvent',
            [
                'old' => $old,
                'new' => $new,
                'repository' => $repository,
            ]
        );
    }

    /**
     * Gets link for the payload
     */
    public function getLink () : string {
        return $this->payload->repository->html_url;
    }

}
