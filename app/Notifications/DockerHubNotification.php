<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Notification;

use InvalidArgumentException;

/**
 * A Docker Hub notification.
 *
 * As we always sort them to the 'docker' group, and the registry only fires
 * one kind of event, this is pretty straightforward without any need for
 * configuration files or analyser class.
 *
 * HOW TO IMPLEMENT PAYLOADS SORT PER REPOSITORY?
 *
 * If you want to extend this to sort Docker images through some rules, we
 * suggest you add a feature request to Docker to include source repository
 * for the image, then call the GitHubPayloadAnalyzer with this repo instead
 * of implementing a new one. This will allows to avoid to maintain two sets
 * of configuration, one for the GitHub repos, one for the Docker repos.
 *
 * Even without that, you can probably be safe with a class or a method to map
 * GitHub and Docker names, either because they are the same, either because
 * there is a prefix: e.g. nasqueron/arcanist and nasqueron/docker-arcanist.
 */
class DockerHubNotification extends Notification {

    public function __construct ($project, $event, $payload) {
        // Straightforward properties
        $this->service = "DockerHub";
        $this->project = $project;
        $this->type = $event;
        $this->rawContent = $payload;
        $this->group = "docker";

        // Properties from the payload
        $this->analyzeByEvent();
    }

    ///
    /// Analyze by event
    ///

    /**
     * Fills properties from event payload.
     */
    public function analyzeByEvent () {
        $analyzer = $this->getAnalyzer();
        $this->rawContent = $analyzer->getPayload();
        $this->text = $analyzer->getText();
        $this->link = $analyzer->getLink();
    }

    /**
     * Gets analyzer class name for the current event.
     *
     * @return string
     */
    private function getAnalyzerClassName () {
        return "Nasqueron\Notifications\Analyzers\DockerHub\\"
             . ucfirst($this->type)
             . "Event";
    }

    /**
     * Gets analyzer for the current event.
     *
     * @return \Nasqueron\Notifications\Analyzers\DockerHub\BaseEvent
     */
    private function getAnalyzer () {
        $class = $this->getAnalyzerClassName();

        if (!class_exists($class)) {
            throw new InvalidArgumentException(
                "Event $this->type doesn't have a matching $class class."
            );
        }

        return new $class($this->rawContent);
    }




}
