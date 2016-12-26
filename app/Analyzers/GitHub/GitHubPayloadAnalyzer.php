<?php

namespace Nasqueron\Notifications\Analyzers\GitHub;

use Nasqueron\Notifications\Analyzers\BasePayloadAnalyzer;
use Nasqueron\Notifications\Analyzers\GitHub\Events\Event;
use Nasqueron\Notifications\Analyzers\GitHub\Events\UnknownEvent;

class GitHubPayloadAnalyzer extends BasePayloadAnalyzer {

    /**
     * The name of the service, used to get specific classes and config
     */
    const SERVICE_NAME = "GitHub";

    ///
    /// Private members
    ///

    /**
     * The GitHub event triggering this request
     * @var string
     */
    private $event;

    /**
     * The payload analyzer event
     *
     * @var \Nasqueron\Notifications\Analyzers\GitHub\Events\Event;
     */
    private $analyzerEvent;

    ///
    /// Constructor
    ///

    /**
     * Creates a new GitHubPayloadAnalyzer instance.
     *
     * @param string $project
     * @param string $event
     * @param \stdClass $payload
     */
    public function __construct($project, $event, $payload) {
        parent::__construct($project, $payload);

        $this->event = $event;

        try {
            $this->analyzerEvent = Event::forPayload($event, $payload);
        } catch (\InvalidArgumentException $ex) {
            $this->analyzerEvent = new UnknownEvent($event);
        }
    }

    ///
    /// Properties
    ///

    /**
     * Gets the name of the item, ie here of the name of the repository.
     *
     * @var string
     */
    public function getItemName () {
        if ($this->isAdministrativeEvent()) {
            return '';
        }

        return $this->payload->repository->name;
    }

    ///
    /// Qualification of the payload
    ///

    /**
     * @return bool
     */
    public function isAdministrativeEvent () {
        $administrativeEvents = [
            'membership',    // Member added to team
            'ping',          // Special ping pong event, fired on new hook
            'repository',    // Repository created
        ];

        return in_array($this->event, $administrativeEvents);
    }

    ///
    /// Description of the payload
    ///

    /**
     * Gets a short textual description of the event.
     *
     * @return string
     */
    public function getDescription () {
        return $this->analyzerEvent->getDescription();
    }

    /**
     * Gets a link to view the event on GitHub.
     *
     * @return string The most relevant URL
     */
    public function getLink () {
      return $this->analyzerEvent->getLink();
    }

}
