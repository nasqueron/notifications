<?php

namespace Nasqueron\Notifications\Analyzers\GitHub\Events;

class Event {

    ///
    /// Properties
    ///

    /**
     * The payload
     *
     * @var \stdClass
     */
    protected $payload;

    ///
    /// Constructor
    ///

    public function __construct ($payload) {
        $this->payload = $payload;
    }

    ///
    /// Gets or initialize relevant class
    ///

    /**
     * Gets class name from the GitHub webhooks event name
     *
     * @param string $eventName The event name (e.g. commit_comment)
     * @return string The event class name (e.g. CommitCommentEvent)
     */
    public static function getClass (string $eventName) : string {
        return __NAMESPACE__ . '\\' . self::toCamelCase($eventName) . 'Event';
    }

    private static function toCamelCase (string $string) : string {
        return str_replace(" ", "", ucwords(str_replace("_", " ", $string)));
    }

    /**
     * Gets an instance of the event class, from the
     *
     * @param string $eventName The event name (e.g. commit_comment)
     */
    public static function forPayload (string $eventName, $payload) : Event {
        $class = self::getClass($eventName);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                "Class doesn't exist: $class (for $eventName)"
            );
        }
        return new $class($payload);
    }

    ///
    /// Helper methods
    ///

    /**
     * Cuts a text
     *
     * @param string $text   The text to cut
     * @param int    $strLen The amount of characters to allow [optional]
     * @param string $symbol The symbol to append to a cut text [optional]
     */
    public static function cut (
        string $text,
        int    $strLen = 114,
        string $symbol = '…'
    ) : string {
        $len = strlen($text);
        if ($len <= $strLen) {
            return $text;
        }

        if ($strLen < 1) {
            return $symbol;
        }

        return substr($text, 0, $strLen - 1) . $symbol;
    }

}
