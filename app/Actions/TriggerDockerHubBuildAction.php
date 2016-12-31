<?php

namespace Nasqueron\Notifications\Actions;

class TriggerDockerHubBuildAction extends Action {
    /**
     * The Docker Hub image
     *
     * @var string
     */
    public $image;

    /**
     * Initializes a new instance of a DockerHub build trigger action to report
     *
     * @param string $image The Docker Hub image to trigger
     */
    public function __construct (string $image) {
        parent::__construct();

        $this->image = $image;
    }
}
