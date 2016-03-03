<?php

namespace Nasqueron\Notifications\Phabricator;

use Cache;

class ProjectsMap implements \IteratorAggregate, \ArrayAccess {

    ///
    /// Private properties and constants
    ///

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
    * @var string
    */
    private $instance;

    /**
    * The source of the map
    *
    * @var string
    */
    private $source = 'unloaded';

    ///
    /// Constructor
    ///

    /**
    * Initializes a new instance of ProjectsMap
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
    public function getIterator () {
        return new \ArrayIterator($this->map);
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
        return array_key_exists($offset, $this->map);
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
    * Gets a new ProjectsMap instance from cache or API when not cached
    *
    * @param string $phabricatorURL The Phabricator URL (e.g. http://secure.phabricator.com)
    * @return ProjectsMap
    */
    public static function load ($phabricatorURL) {
        $instance = new self($phabricatorURL);

        if ($instance->isCached()) {
            $instance->loadFromCache();
        } else {
            $instance->fetchFromAPI();
        }

        return $instance;
    }

    /**
    * Gets a new ProjectsMap instance and queries Phabricator API to fill it
    *
    * @param string $phabricatorURL The Phabricator URL (e.g. http://secure.phabricator.com)
    * @return ProjectsMap
    */
    public static function fetch ($phabricatorURL) {
        $instance = new self($phabricatorURL);
        $instance->fetchFromAPI();
        return $instance;
    }

    ///
    /// API
    ///

    /**
    * Fetches the projects' map from the Phabricator API
    */
    private function fetchFromAPI () {
        $reply = \PhabricatorAPI::get($this->instance)->call(
            'project.query',
            [ 'limit' => self::LIMIT ]
        );

        if (!$reply) {
            throw new \Exception("Empty reply calling project.query at $this->instance API.");
        }

        if (!property_exists($reply, 'data')) {
            throw new \Exception("Invalid reply calling project.query at $this->instance API.");
        }

        foreach ($reply->data as $phid => $projectInfo) {
            $this->offsetSet($phid, $projectInfo->name);
        }

        $this->source = 'api';
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

    /**
    * Determines if the instance is cached
    *
    * @return bool true if cached; otherwise, false.
    */
    public function isCached () {
        return Cache::has($this->getCacheKey());
    }

    /**
    * Saves data to cache
    */
    public function saveToCache () {
        Cache::forever($this->getCacheKey(), $this->map);
    }

    /**
    * Loads data from cache
    *
    * Populates 'map' and 'source' properties
    */
    public function loadFromCache () {
        $cachedMap = Cache::get($this->getCacheKey());
        if ($cachedMap !== null) {
            $this->map = $cachedMap;
            $this->source = 'cache';
        }
    }

    ///
    /// Output
    ///

    /**
    * Gets project name, refreshing the cache if needed
    *
    * @param string $projectPHID the PHID of the project to query the name
    * @return string
    */
    public function getProjectName ($projectPHID) {
        if ($this->offsetExists($projectPHID)) {
            return $this->offsetGet($projectPHID);
        }

        if ($this->source !== 'api') {
            $this->fetchFromAPI();
            return $this->getProjectName($projectPHID);
        }

        return "";
    }

    /**
    * Returns the projects map as an array, each row ['PHID', 'project name']
    *
    * @return array
    */
    public function toArray () {
        $array = [];
        foreach ($this->map as $phid => $projectName) {
            $array[] = [$phid, $projectName];
        }
        return $array;
    }
}
