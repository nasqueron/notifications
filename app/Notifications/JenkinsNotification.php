<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzer;
use Nasqueron\Notifications\Notification;

/**
 * A Jenkins notification.
 *
 * This handles the JSON payloads sent by the following plugin:
 * https://wiki.jenkins-ci.org/display/JENKINS/Notification+Plugin
 */
class JenkinsNotification extends Notification {

    /**
     * @var \Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzer
     */
    private $analyzer = null;

    /**
     * Initializes a new instance of the JenkinsNotification class.
     *
     * @param string $project The project this message is for
     * @param mixed $payload The message fired by Jenkins notification plugin
     */
    public function __construct ($project, $payload) {
        // Straightforward properties
        $this->service = "Jenkins";
        $this->project = $project;
        $this->rawContent = $payload;

        // Properties from the payload
        $this->group = $this->getGroup();
        $this->text = $this->getText();
        $this->link = $payload->build->full_url;
        $this->type = $this->getType();
    }

    /**
     * Gets the notification type.
     *
     * @return string
     */
    public function getType () {
        $build = $this->rawContent->build;

        $type = strtolower($build->phase);

        if (property_exists($build, 'status')) {
            $type .= '.';
            $type .= $build->status;
        }

        return strtolower($type);
    }

    /**
     * Gets the notification text. Intended to convey a short message (thing Twitter or IRC).
     *
     * @return string
     */
    public function getText () {
        $name = $this->rawContent->name;

        $build = $this->rawContent->build;
        $phase = strtolower($build->phase);

        $text = "Jenkins job $name has been $phase";

        if (property_exists($build, 'status')) {
            $status = strtolower($build->status);
            $text .= ": $status";
        }

        return $text;
    }

    /**
     * Gets analyzer
     *
     * @return \Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzer
     */
    private function getAnalyzer () {
        if ($this->analyzer === null) {
            $this->analyzer = new JenkinsPayloadAnalyzer(
                $this->project,
                $this->rawContent
            );
        }
        return $this->analyzer;
    }

    /**
     * Gets the notification group.
     *
     * @return string
     */
    public function getGroup () {
        return $this->getAnalyzer()->getGroup();
    }

}
