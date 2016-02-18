<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorPayloadAnalyzer;
use Nasqueron\Notifications\Notification;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

class PhabricatorNotification extends Notification {

    /**
     * @var PhabricatorPayloadAnalyzer
     */
    private $analyzer = null;

    private $story;


    /**
     * Initializes a new PhabricatorNotification instance
     *
     * @param string $project The project for this notification
     * @param PhabricatorStory $story The story to convert into a notification
     * @param string[] $projects the list of the projects for this story
     */
    public function __construct ($project, PhabricatorStory $story) {
        // Private property used by the analyzer
        $this->story = $story;

        // Straightforward properties
        $this->service = "Phabricator";
        $this->project = $project;
        $this->rawContent = json_encode($story);
        $this->text = $story->text;

        // Analyzes and fills
        $this->type = $story->getObjectType();
        $this->group = $this->getGroup();
        $this->link = $this->getLink();
    }

    /**
     * Gets analyzer
     */
    private function getAnalyzer () {
        if ($this->analyzer === null) {
            $this->analyzer = new PhabricatorPayloadAnalyzer(
                $this->project,
                $this->story
            );
        }
        return $this->analyzer;
    }

    /**
     * Gets the target notificatrion group
     *
     * @return string the target group for the notification
     */
    public function getGroup () {
        return $this->getAnalyzer()->getGroup();
    }

    /**
     * Gets the notification URL. Intended to be a widget or icon link.
     *
     * @return string
     */
    public function getLink () {
        return "";
    }

}
