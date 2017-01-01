<?php

namespace Nasqueron\Notifications\Config\Reporting;

abstract class BaseReportEntry {

    ///
    /// Format
    ///

    public abstract function toArray () : array;
    public abstract function toFancyArray () : array;

    ///
    /// Format helper methods
    ///

    /**
     * Returns a fancy string for reports.
     *
     * @param string $string The source string
     * @param string $emptyStringGlyph The glyph to use if the string is empty
     * @return string
     */
    public static function fancyString (string $string, string $emptyStringGlyph) : string {
        if ($string === "") {
            return $emptyStringGlyph;
        }

        return $string;
    }

    /**
     * Returns a fancy representation from a boolean for reports.
     *
     * @param bool $value The source value
     * @param string $truthyStringGlyph The glyph to use if the value is true
     * @param string $falsyStringGlyph The glyph to use if the value is false [facultative, by default an empty string]
     * @return string The relevant glyph
     */
    public static function fancyBool (bool $value, string $truthyStringGlyph, string $falsyStringGlyph = '') : string {
        if ($value) {
            return $truthyStringGlyph;
        }

        return $falsyStringGlyph;
    }

}
