<?php

namespace Nasqueron\Notifications\Phabricator;

class PhabricatorStory {

    ///
    /// Properties
    ///

    /**
     * The Phabricator main URL
     *
     * @var string
     */
    public $instance;

    /**
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
     * @var Array
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
     * The time the event occured
     *
     * @var int
     */
    public $epoch;

    ///
    /// Constructors
    ///

    /**
     * Initializes a new instance of the Phabricator story class
     *
     * @param string $instance The Phabricator main URL, without trailing slash
     */
    public function __construct ($instance) {
        $this->instance = $instance;
    }

    /**
     * Initializes a new instance of PhabricatorStory from an array.
     *
     * This is intended to parse the feed.hooks payloads.
     *
     * @param string $phabricatorURL The Phabricator URL (e.g. http://secure.phabricator.com)
     * @param string $payload The data submitted by Phabricator
     * @return PhabricatorStory
     */
    public static function loadFromArray ($phabricatorURL, $payload) {
        $instance = new self($phabricatorURL);

        foreach ($payload as $key => $value) {
            $property = self::mapPhabricatorFeedKey($key);
            $instance->$property = $value;
        }

        return $instance;
    }

    ///
    /// Helper methods
    ///

    /**
     * Gets object type (e.g. TASK for PHID-TASK-l34fw5wievp6n6rnvpuk)
     *
     * @return string The object type, as a 4 letters string (e.g. 'TASK')
     */
    public function getObjectType () {
        return substr($this->data['objectPHID'], 5, 4);
    }

    public function getProjectsPHIDs () {
        $objectPHID = $this->data['objectPHID'];
        $objectType = $this->getObjectType();

        switch ($objectType) {
            case 'DREV':
                return $this->getItemProjectsPHIDs(
                    'repository.query',
                    $this->getRepositoryPHID('differential.query')
                );
                break;

            case 'TASK':
                return $this->getItemProjectsPHIDs(
                    'maniphest.query',
                    $objectPHID
                );

            case 'CMIT':
                // TODO: call API, return projects PHIDs
                return $this->getItemProjectsPHIDs(
                    'repository.query',
                    $this->getRepositoryPHID('diffusion.querycommits')
                );
                break;

            default:
                return [];
        }
    }

    public function getRepositoryPHID ($method) {
        $objectPHID = $this->data['objectPHID'];
        $api = PhabricatorAPI::forInstance($this->instance);
        $reply = $api->call(
            $method,
            [ 'phids[0]' => $objectPHID ]
        );
        return $reply->$objectPHID->repositoryPHID;
    }

    public function getItemProjectsPHIDs ($method, $objectPHID) {
        $api = PhabricatorAPI::forInstance($this->instance);
        $reply = $api->call(
            $method,
            [ 'phids[0]' => $objectPHID ]
        );
        return $reply->$objectPHID->projectPHIDs;
    }

    public function getProjects () {
        $PHIDs = $this->getProjectsPHIDs();

        if (count($PHIDs) == 0) {
            // No project is attached to the story's object
            return [];
        }

        $projects = [];

        $map = ProjectsMap::load($this->instance);
        foreach ($PHIDs as $PHID) {
            $projects[] = $map->getProjectName($PHID);
        }
        return $projects;
    }

    ///
    /// Static helper methods
    ///

    public static function mapPhabricatorFeedKey ($key) {
        if ($key == "storyID") {
            return "id";
        }

        if (starts_with($key, "story")) {
            $key = substr($key, 5);
            $key[0] = strtolower($key[0]); // lowercase
        }

        return $key;
    }

}
