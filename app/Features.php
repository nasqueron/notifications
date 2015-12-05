<?php

namespace Nasqueron\Notifications;

use Config;

/**
 * The features class offers a sugar syntax to check if a feature is enabled
 * in the Config repository, at app.features.
 *
 * Features could be added to config/app.php to the features array.
 */
class Features {

    /**
     * Determines if the specified feature is enabled
     *
     * @param string $feature The feature to check in the config
     * @return bool
     */
    public static function isEnabled ($feature) {
        $key = 'app.features.' . $feature;
        return Config::has($key) && (bool)Config::get($key);
    }

    /**
     * Gets all the features, with the toggle status
     *
     * @return array An array with features as keys, bool as values (true if enabled)
     */
    public static function getAll () {
       return Config::get('app.features');
    }

    /**
     * Lists all the features
     *
     * @return string[] a list of all features
     */
    public static function getAvailable () {
        $features = self::getAll();
        return array_keys($features);
    }

    /**
     * Lists the enabled features
     *
     * @return string[] a list of enabled features
     */
    public static function getEnabled () {
        $features = self::getAll();
        $enabledFeatures = array_filter($features);
        return array_keys($enabledFeatures);
    }

}
