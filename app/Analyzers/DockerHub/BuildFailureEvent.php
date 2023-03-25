<?php

namespace Nasqueron\Notifications\Analyzers\DockerHub;

use Nasqueron\Notifications\Facades\Mailgun;

class BuildFailureEvent extends BaseEvent {

    /**
     * Initializes a new instance of the BuildFailureEvent object.
     *
     * @param \stdClass $payload The payload to analyze
     */
    public function __construct (\stdClass $payload) {
        parent::__construct($payload);
        $this->payload = $this->getMailGunPayload();
    }

    /**
     * Gets a MailGun message.
     */
    private function getMailGunPayload () : \stdClass {
        return Mailgun::fetchMessageFromPayload($this->payload);
    }

    private function getMailBody () : string {
        $bodyProperty = 'body-plain';
        return $this->payload->$bodyProperty;
    }

    /**
     * Extracts a regular expression from the mail body.
     *
     * @param $string Regular expression
     * @return string
     */
    private function extractFromBody ($regex) : string {
        preg_match($regex, $this->getMailBody(), $matches);
        return $matches[1];
    }

    /**
     * Gets text from payload.
     */
    public function getText() : string {
        $repo = $this->extractFromBody("@\"(.*?\/.*?)\"@");

        return "Image build by Docker Hub registry failure for $repo";
    }

    /**
     * Gets link from payload.
     */
    public function getLink() : string {
        return $this->extractFromBody("@(https\:\/\/hub.docker.com\/r.*)@");
    }

}
