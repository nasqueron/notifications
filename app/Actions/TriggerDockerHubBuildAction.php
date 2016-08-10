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
     */
    public function __construct ($image) {
        parent::__construct();

        $this->image = $image;
    }
}
