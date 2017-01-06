<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorPayloadAnalyzer;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

class PhabricatorNotification extends Notification {

    /**
     * @var PhabricatorPayloadAnalyzer
     */
    private $analyzer = null;

    /**
     * Initializes a new PhabricatorNotification instance
     *
     * @param string $project The project for this notification
     * @param PhabricatorStory $payload The story to convert into a notification
     */
    public function __construct (string $project, PhabricatorStory $payload) {
        // Straightforward properties
        $this->service = "Phabricator";
        $this->project = $project;
        $this->rawContent = $payload;
        $this->text = $payload->text;

        // Analyzes and fills
        $this->type = $payload->getObjectType();
        $this->group = $this->getGroup();
        $this->link = $this->getLink();
    }

    /**
     * Gets analyzer
     *
     * @return \Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorPayloadAnalyzer
     */
    private function getAnalyzer () : PhabricatorPayloadAnalyzer {
        if ($this->analyzer === null) {
            $this->analyzer = new PhabricatorPayloadAnalyzer(
                $this->project,
                $this->rawContent
            );
        }
        return $this->analyzer;
    }

    /**
     * Gets the target notificatrion group
     *
     * @return string the target group for the notification
     */
    public function getGroup () : string {
        return $this->getAnalyzer()->getGroup();
    }

    /**
     * Gets the notification URL. Intended to be a widget or icon link.
     *
     * @return string
     */
    public function getLink () : string {
        return "";
    }

}
