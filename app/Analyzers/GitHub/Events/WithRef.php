<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

/**
 * References helper methods for events using ref and ref_type fields.
 * (e.g. create and delete)
 *
 * @link https://developer.github.com/v3/activity/events/types/#createevent
 * @link https://developer.github.com/v3/activity/events/types/#deleteevent
 */
trait WithRef {

    /**
     * Determines if the ref type is valid.
     *
     * The ref type 'repository' is deemed invalid, as we shouldn't receive it.
     *
     * @param string $type The ref type to check
     * @return bool true if the ref type id valid; otherwise, false
     */
    protected static function isValidRefType ($type) {
        $types = ['branch', 'tag'];
        return in_array($type, $types);
    }

    /**
     * Gets link ref segment for the payload
     *
     * @param string $type The reference type
     * @return string the part of the URL for this reference type (e.g. /tree/)
     */
    protected function getLinkRefSegment ($type) {
        $segments = $this->getLinkRefSegments();

        if (!array_key_exists($type, $segments)) {
            throw new \InvalidArgumentException;
        }

        return $segments[$type];
    }

}
