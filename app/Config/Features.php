<?php

namespace Nasqueron\Notifications\Config;

use Config;

/**
 * The features class offers a sugar syntax to check if a feature is enabled
 * in the Config repository, at app.features.
 *
 * Features could be added to config/app.php to the features array.
 */
class Features {

    ///
    /// Feature information
    ///

    /**
     * @param string $feature The feature to get the config key
     * @return string The config key
     */
    private static function getFeatureConfigKey (string $feature) : string {
        return 'app.features.' . $feature;
    }

    /**
     * Determines if the specified feature is enabled.
     *
     * @param string $feature The feature to check in the config
     * @return bool
     */
    public static function isEnabled (string $feature) : bool {
        $key = self::getFeatureConfigKey($feature);
        return Config::has($key) && (bool)Config::get($key);
    }

    /**
     * Enables a feature in our current configuration instance.
     *
     * @param string $feature The feature
     */
    public static function enable (string $feature) : void {
        $key = self::getFeatureConfigKey($feature);
        Config::set($key, true);
    }

    /**
     * Disables a feature in our current configuration instance.
     *
     * @param string $feature The feature
     */
    public static function disable (string $feature) : void {
        $key = self::getFeatureConfigKey($feature);
        Config::set($key, false);
    }

    ///
    /// Features lists
    ///

    /**
     * Gets all the features, with the toggle status.
     *
     * @return array An array with features as keys, bool as values (true if enabled)
     */
    public static function getAll () : array {
       return Config::get('app.features');
    }

    /**
     * Lists all the features.
     *
     * @return string[] a list of all features
     */
    public static function getAvailable () : array {
        $features = self::getAll();
        return array_keys($features);
    }

    /**
     * Lists the enabled features
     *
     * @return string[] a list of enabled features
     */
    public static function getEnabled () : array {
        $features = self::getAll();
        $enabledFeatures = array_filter($features);
        return array_keys($enabledFeatures);
    }
}
