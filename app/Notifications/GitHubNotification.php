<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer;
use Nasqueron\Notifications\Notification;

class GitHubNotification extends Notification {

    /**
     * @var GitHubPayloadAnalyzer
     */
    private $analyzer = null;

    public function __construct ($project, $event, $payload) {
        // Straightforward properties
        $this->service = "GitHub";
        $this->project = $project;
        $this->type = $event;
        $this->rawContent = $payload;

        // Analyzes and fills
        $this->group = $this->getGroup();
        $this->text = $this->getText();
        $this->link = $this->getLink();
    }

    /**
     * Gets analyzer
     */
    private function getAnalyzer () {
        if ($this->analyzer === null) {
            $this->analyzer = new GitHubPayloadAnalyzer(
                $this->project,
                $this->type,
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
    public function getGroup () {
        return $this->getAnalyzer()->getGroup();
    }

    /**
     * Gets the notification text. Intended to convey a short message (thing Twitter or IRC).
     *
     * @return string
     */
    public function getText () {
        return $this->getAnalyzer()->getDescription();
    }

    /**
     * Gets the notification URL. Intended to be a widget or icon link.
     *
     * @return string
     */
    public function getLink () {
        return $this->getAnalyzer()->getLink();
    }

}
