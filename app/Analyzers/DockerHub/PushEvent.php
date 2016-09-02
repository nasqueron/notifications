<?php

namespace Nasqueron\Notifications\Analyzers\DockerHub;

class PushEvent extends BaseEvent {

    /**
     * Gets text from payload.
     *
     * @return string
     */
    public function getText() {
        $repo = $this->payload->repository->repo_name;
        $who = $this->payload->push_data->pusher;

        return "New image pushed to Docker Hub registry for $repo by $who";
    }

    /**
     * Gets link from payload.
     *
     * @return string
     */
    public function getLink() {
        return $this->payload->repository->repo_url;
    }

}
