<?php

namespace Nasqueron\Notifications\Analyzers\DockerHub;

class PushEvent extends BaseEvent {

    /**
     * Gets text from payload.
     */
    public function getText() : string {
        $repo = $this->payload->repository->repo_name;
        $who = $this->payload->push_data->pusher;

        return "New image pushed to Docker Hub registry for $repo by $who";
    }

    /**
     * Gets link from payload.
     */
    public function getLink() : string {
        return $this->payload->repository->repo_url;
    }

}
