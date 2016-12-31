<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * DeleteEvent payload analyzer
 *
 * Another case when we won't receive the event is when at least four tags are
 * deleted at once.
 *
 * @link https://developer.github.com/v3/activity/events/types/#deleteevent
 */
class DeleteEvent extends Event {

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
                'GitHub.EventsDescriptions.DeleteEventUnknown',
                [
                    'type' => $type,
                    'ref' => $ref,
                ]
            );
        }

        return trans(
            'GitHub.EventsDescriptions.DeleteEvent',
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
     * @return Array
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function getLinkRefSegments () {
        return [
             'tag' => '/tags',
             'branch' => '/branches',
        ];
    }

    /**
     * Gets link for the payload
     *
     * @return string
     */
    public function getLink () : string {
        $type = $this->payload->ref_type;

        $url  = $this->payload->repository->html_url;
        $url .= $this->getLinkRefSegment($type);

        return $url;
    }
}
