<?php

namespace Nasqueron\Notifications\Notifications;

use Nasqueron\Notifications\Notification;

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
        $this->text = $this->getText();
        $this->link = $payload->repository->repo_url;
    }

    /**
     * Gets the notification text. Intended to convey a short message (thing Twitter or IRC).
     *
     * @return string
     */
    public function getText () {
        $repo = $this->rawContent->repository->repo_name;
        $who = $this->rawContent->push_data->pusher;
        return "New image pushed to Docker Hub registry for $repo by $who";
    }

}
