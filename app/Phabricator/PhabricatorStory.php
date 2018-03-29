<?php

namespace Nasqueron\Notifications\Phabricator;

use InvalidArgumentException;

class PhabricatorStory {

    ///
    /// Properties
    ///

    /**
     * The Phabricator instance name
     *
     * @var string
     */
    public $instanceName;

    /**
     * The unique identifier Phabricator assigns to each story
     *
     * @var int
     */
    public $id;

    /**
     * Type of story (e.g. PhabricatorApplicationTransactionFeedStory)
     *
     * @var string
     */
    public $type;

    /**
     * @var array|null
     */
    public $data;

    /**
     * The person logged to Phabricator and triggering the event
     *
     * @var string
     */
    public $authorPHID;

    /**
     * A short English textual description of the event
     *
     * @var string
     */
    public $text;

    /**
     * The unixtime the event occured
     *
     * @var int
     */
    public $epoch;

    /**
     * The projects attached to this story.
     *
     * When there is no project, [].
     * When not yet queried, null.
     *
     * @var string[]|null
     */
    private $projects = null;

    ///
    /// Constructors
    ///

    /**
     * Initializes a new instance of the Phabricator story class
     *
     * @param string $instanceName The Phabricator instance name
     */
    public function __construct ($instanceName) {
        $this->instanceName = $instanceName;
    }

    /**
     * Initializes a new instance of PhabricatorStory from an iterable.
     *
     * This is intended to parse the feed.hooks payloads.
     *
     * @param string $instanceName The Phabricator instance name
     * @param iterable $payload The data submitted by Phabricator
     * @return PhabricatorStory
     */
    public static function loadFromIterable (string $instanceName, iterable $payload) {
        $instance = new self($instanceName);

        foreach ($payload as $key => $value) {
            $property = self::mapPhabricatorFeedKey($key);
            $instance->$property = $value;
        }

        return $instance;
    }

    /**
     * Initializes a new instance of PhabricatorStory from a JSON payload.
     *
     * This is intended to parse files saved by LastPayloadSaver::savePayload.
     *
     * @param string $instanceName The Phabricator instance name
     * @param string $payload The data submitted by Phabricator's JSON representation
     * @return PhabricatorStory
     */
    public static function loadFromJson ($instanceName, $payload) {
        $array = json_decode($payload, true);

        if (!is_array($array)) {
            throw new InvalidArgumentException("Payload should be deserializable as an array.");
        }

        return self::loadFromIterable($instanceName, $array);
    }

    ///
    /// Helper methods
    ///

    private function hasVoidObjectType () : bool {
        return $this->data === null || !isset($this->data['objectPHID']);
    }

    /**
     * Gets object type (e.g. TASK for PHID-TASK-l34fw5wievp6n6rnvpuk)
     *
     * @return string The object type, as a 4 letters string (e.g. 'TASK')
     */
    public function getObjectType () {
        if ($this->hasVoidObjectType()) {
            return 'VOID';
        }

        return substr($this->data['objectPHID'], 5, 4);
    }

    /**
     * Gets the identifier of the projets related to this task
     *
     * return string[] The list of project PHIDs
     */
    public function getProjectsPHIDs () {
        if (!array_key_exists('objectPHID', $this->data)) {
            return [];
        }

        $objectPHID = $this->data['objectPHID'];
        $objectType = $this->getObjectType();

        switch ($objectType) {
            case 'DREV':
                return $this->getItemProjectsPHIDs(
                    'repository.query',
                    $this->getRepositoryPHID('differential.query')
                );

            case 'TASK':
                return $this->getItemProjectsPHIDs(
                    'maniphest.query',
                    $objectPHID
                );

            case 'CMIT':
                return $this->getItemProjectsPHIDs(
                    'repository.query',
                    $this->getRepositoryPHID('diffusion.querycommits')
                );

            case 'PSTE':
                return $this->getItemProjectsPHIDsThroughApplicationSearch(
                    'paste.search',
                    $objectPHID
                );

            default:
                return [];
        }
    }

    /**
     * Gets the PHID of a repository
     *
     * @param string $method The API method to call (e.g. differential.query)
     * @return string The repository PHID or "" if not found
     */
    public function getRepositoryPHID ($method) {
        $objectPHID = $this->data['objectPHID'];

        $api = PhabricatorAPI::forProject($this->instanceName);
        $reply = $api->call(
            $method,
            [ 'phids[0]' => $objectPHID ]
        );

        if ($reply === []) {
            return "";
        }

        $apiResult = PhabricatorAPI::getFirstResult($reply);
        if ($apiResult === null) {
            // Repository information can't be fetched (T1136).
            // This occurs when the bot account used to fetch information
            // doesn't have access to the repository, for example if it's
            // in a private space or restricted to a group it doesn't belong to.
            return "";
        }

        return $apiResult->repositoryPHID;
    }

    /**
     * Gets the projects for a specific item
     *
     * @param string $method The API method to call (e.g. differential.query)
     * @param string $objectPHID The object PHID to pass as method parameter
     * @return string[] The list of project PHIDs
     */
    public function getItemProjectsPHIDs ($method, $objectPHID) {
        if (!$objectPHID) {
            return [];
        }

        $api = PhabricatorAPI::forProject($this->instanceName);
        $reply = $api->call(
            $method,
            [ 'phids[0]' => $objectPHID ]
        );

        if ($reply === []) {
            return [];
        }

        return PhabricatorAPI::getFirstResult($reply)->projectPHIDs;
    }

    /**
     * Gets the project for a specific item, using the new ApplicationSearch.
     *
     * This is a transitional method: when every Phabricator will have been
     * migrated from info (generation 1) or query (generation 2) to search
     * (generation 3), we'll rename it to getItemProjectsPHIDs and overwrite it.
     */
     protected function getItemProjectsPHIDsThroughApplicationSearch  ($method, $objectPHID) {
        if (!$objectPHID) {
            return [];
        }

        $api = PhabricatorAPI::forProject($this->instanceName);
        $reply = $api->call(
            $method,
            [
                'constraints[phids][0]' => $objectPHID,
                'attachments[projects]' => 1
            ]
        );

        $apiResult = PhabricatorAPI::getFirstResult($reply);
        if ($apiResult === null) {
            // Object information (e.g. a paste) can't be fetched (T1138).
            // This occurs when the bot account used to fetch information
            // doesn't have access to the object, for example if it's
            // in a private space or restricted to a group it doesn't belong to.
            return [];
        }

        return $apiResult->attachments->projects->projectPHIDs;
     }

    /**
     * Gets the list of the projects associated to the story
     *
     * @return string[] The list of project PHIDs
     */
    public function getProjects () {
        if ($this->projects === null) {
            $this->attachProjects();
        }
        return $this->projects;
    }

    /**
     * Queries the list of the projects associated to the story
     * and attached it to the projects property.
     */
    public function attachProjects () {
        $this->projects = [];

        $PHIDs = $this->getProjectsPHIDs();

        if (count($PHIDs) == 0) {
            // No project is attached to the story's object
            return;
        }

        $map = ProjectsMap::load($this->instanceName);
        foreach ($PHIDs as $PHID) {
            $this->projects[] = $map->getProjectName($PHID);
        }
    }

    ///
    /// Static helper methods
    ///

    /**
     * Maps a field of the API reply to a property of the PhabricatorStory class
     *
     * @param string $key The field of the API reply
     * @return string The property's name
     */
    public static function mapPhabricatorFeedKey ($key) {
        if ($key == "storyID") {
            return "id";
        }

        if (starts_with($key, "story") && strlen($key) > 5) {
            return lcfirst(substr($key, 5));
        }

        return $key;
    }

}
