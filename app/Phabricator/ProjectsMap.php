<?php

namespace Nasqueron\Notifications\Phabricator;

use Nasqueron\Notifications\Contracts\APIClient as APIClient;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

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
     * The Phabricator instance name for this projects map
     *
     * @var string
     */
    private $instanceName;

    /**
     *
     * @var \Nasqueron\Notifications\Contracts\APIClient
     */
    private $apiClient;

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
     * Initializes a new instance of ProjectsMap.
     *
     * @param string $instanceName The Phabricator instance name
     */
    public function __construct (string $instanceName) {
        $this->instanceName = $instanceName;
    }

    ///
    /// IteratorAggregate interface implementation
    ///

    /**
     * Gets iterator.
     */
    public function getIterator () : \Traversable {
        return new \ArrayIterator($this->map);
    }

    ///
    /// ArrayAccess interface implementation
    ///

    /**
     * Determines whether an offset exists.
     *
     * @param mixed $offset The offset
     * @return bool
     */
    public function offsetExists (mixed $offset) : bool {
        return array_key_exists($offset, $this->map);
    }

    /**
     * Gets the value at the specified offset.
     *
     * @param mixed $offset The offset.
     * @return mixed The value
     */
    public function offsetGet (mixed $offset) : mixed {
        return $this->map[$offset];
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @param mixed $offset The offset
     * @param mixed $value The value to assign
     */
    public function offsetSet (mixed $offset, mixed $value) : void {
        $this->map[$offset] = $value;
    }

    /**
     * Unsets a value at the specified offset.
     *
     * @param mixed $offset The offset where to remove the value
     */
    public function offsetUnset (mixed $offset) : void {
        unset($this->map[$offset]);
    }

    ///
    /// Static constructors
    ///

    /**
     * Gets a new ProjectsMap instance from cache or API when not cached.
     *
     * @param string $phabricatorInstanceName The Phabricator instance name
     * @return ProjectsMap
     */
    public static function load (string $phabricatorInstanceName) : ProjectsMap {
        $instance = new self($phabricatorInstanceName);

        if ($instance->isCached()) {
            $instance->loadFromCache();
        } else {
            $instance->fetchFromAPI();
        }

        return $instance;
    }

    /**
     * Gets a new ProjectsMap instance and queries Phabricator API to fill it.
     */
    public static function fetch (
        string $phabricatorInstanceName,
        ?APIClient $apiClient = null
    ) : ProjectsMap {
        $instance = new self($phabricatorInstanceName);
        $instance->setAPIClient($apiClient);
        $instance->fetchFromAPI();
        return $instance;
    }

    ///
    /// API
    ///

    public function getAPIClient () : APIClient {
        if ($this->apiClient === null) {
            $factory = App::make('phabricator-api');
            $this->apiClient = $factory->getForProject($this->instanceName);
        }
        return $this->apiClient;
    }

    public function setAPIClient (?APIClient $apiClient = null) : void {
        $this->apiClient = $apiClient;
    }

    /**
     * Fetches the projects' map from the Phabricator API.
     *
     * @throws \Exception when API reply is empty or invalid.
     */
    private function fetchFromAPI () {
        $reply = $this->getAPIClient()->call(
            'project.query',
            [ 'limit' => self::LIMIT ]
        );

        if (!$reply) {
            throw new \Exception(<<<MSG
Empty reply calling project.query at $this->instanceName Conduit API.
MSG
);
        }

        if (!property_exists($reply, 'data')) {
            throw new \Exception(<<<MSG
Invalid reply calling project.query at $this->instanceName Conduit API.
MSG
);
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
     * Gets cache key.
     *
     * @return string The cache key for the current projects map
     */
    private function getCacheKey () : string {
        return class_basename(get_class($this))
               . '-'
               . md5($this->instanceName);
    }

    /**
     * Determines if the instance is cached
     *
     * @return bool true if cached; otherwise, false.
     */
    public function isCached () : bool {
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
     * Gets project name, refreshing the cache if needed.
     *
     * @param string $projectPHID the PHID of the project to query the name
     * @return string The name of the poject, or an empty string if not found
     */
    public function getProjectName (string $projectPHID) : string {
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
     * Returns the projects map as an array.
     *
     * @return array[] An array, each row containing ['PHID', 'project name']
     */
    public function toArray () : array {
        $array = [];
        foreach ($this->map as $phid => $projectName) {
            $array[] = [$phid, $projectName];
        }
        return $array;
    }
}
