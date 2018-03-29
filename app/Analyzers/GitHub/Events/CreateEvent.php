<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * CreateEvent payload analyzer
 *
 * We don't support the repository ref type, as, according the documentation,
 * "webhooks will not receive this event for created repositories".webhooks
 *
 * Another case when we won't receive the event is when at least four tags are
 * pushed at once.
 *
 * @link https://developer.github.com/v3/activity/events/types/#createevent
 */
class CreateEvent extends Event {

    use WithRef;

    /**
     * Gets description for the payload
     *
     * @return string
     */
    public function getDescription () : string {
        $repository = $this->payload->repository->full_name;
        $type = $this->payload->ref_type;
        $ref = $this->payload->ref;

        if (!self::isValidRefType($type)) {
            return trans(
                'GitHub.EventsDescriptions.CreateEventUnknown',
                [
                    'type' => $type,
                    'ref' => $ref,
                ]
            );
        }

        return trans(
            'GitHub.EventsDescriptions.CreateEvent',
            [
                'type' => $type,
                'ref' => $ref,
                'repository' => $repository,
            ]
        );
    }

    /**
     * Gets link segments for the type
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function getLinkRefSegments () : array {
        return [
            'tag' => '/releases/tag/',
            'branch' => '/tree/',
        ];
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () : string {
        $type = $this->payload->ref_type;
        $ref  = $this->payload->ref;

        $url  = $this->payload->repository->html_url;
        $url .= $this->getLinkRefSegment($type);
        $url .= $ref;

        return $url;
    }
}
