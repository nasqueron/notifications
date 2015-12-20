<?php

namespace Nasqueron\Notifications\Phabricator;

use Cache;

class ProjectMap implements \IteratorAggregate, \ArrayAccess {

    ///
    /// Private properties and constants

    /**
     * The maximum number of projects to fetch
     */
    const LIMIT = 1000;

    /**
     * The projects as an array with phid as keys, project names as $value
     *
     * @var string[]
     */
    private $map = [];

    /**
     * The Phabricator instance for this projects map
     *
     * @var map
     */
    private $instance;

    ///
    /// Constructor
    ///

    /**
     * Initializes a new instance of ProjectMap
     *
     * @param string $instance The Phabricator root URL without trailing slash
     */
    public function __construct ($instance) {
        $this->instance = $instance;
    }

    ///
    /// IteratorAggregate interface implementation
    ///

    /**
     * Gets iterator
     *
     * @return Traversable
     */
    public function getIterator ( ) {
        return new ArrayIterator($this->map);
    }

    ///
    /// ArrayAccess interface implementation
    ///

    /**
     * Determines whether an offset exists
     *
     * @param mixed $offset The offset
     */
    public function offsetExists ($offset) {
        return array_key_exists($this->map[$offset]);
    }

    /**
     * Gets an offset
     *
     * @param mixed $offset The offset
     * @return mixed The value
     */
    public function offsetGet ($offset) {
        return $this->map[$offset];
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param mixed $offset The offset
     * @param mixed $value The value to assign
     */
    public function offsetSet ($offset, $value) {
        $this->map[$offset] = $value;
    }

    /**
     * Unset an offset
     *
     * @param mixed $offset The offset
     */
    public function offsetUnset ($offset) {
        unset($this->map[$offset]);
    }

    ///
    /// Static constructors
    ///

    /**
     * Gets a new ProjectMap instance and queries Phabricator API to fill it.
     *
     * @param string $phabricatorURL The Phabricator URL (e.g. http://secure.phabricator.com)
     * @param string $apiToken The API token from .../settings/panel/apitokens/
     */
    public static function fetch ($phabricatorURL, $apiToken) {
       $instance = new self($phabricatorURL);

       $api = new PhabricatorAPI($phabricatorURL, $apiToken);
       $reply = $api->call(
           'project.query',
           [ 'limit' => self::LIMIT ]
        );

       if (!$reply) {
           throw new \Exception("Empty reply calling project.query at $phabricatorURL API.");
       }

       if (!property_exists($reply, 'data')) {
           throw new \Exception("Invalid reply calling project.query at $phabricatorURL API.");
       }

       foreach ($reply->data as $phid => $projectInfo) {
           $instance[$phid] = $projectInfo->name;
       }

       return $instance;
    }

    ///
    /// Cache
    ///

    /**
     * Gets cache key
     *
     * @return string The cache key for the current projects map
     */
    private function getCacheKey () {
        return class_basename(get_class($this)) . '-' . md5($this->instance);
    }

    public function isCached () {
        return Cache::has($this->getCacheKey());
    }

    public function save () {
        Cache::forever($this->getCacheKey(), $this->map);
    }

    public function load () {
        $this->map = Cache::get($this->getCacheKey());
    }

    ///
    /// Output
    ///

    /**
     * Returns the projects map as an array, each row ['PHID', 'project name']
     *
     * @return Array
     */
    public function toArray () {
        $array = [];
        foreach ($this->map as $phid => $projectName) {
            $array[] = [$phid, $projectName];
        }
        return $array;
    }
}
