<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\PullRequestEvent;
use Nasqueron\Notifications\Tests\TestCase;

class PullRequestEventTest extends TestCase {

    /**
     * @var \stdClass
     */
    private $payload;

    public function setUp (): void {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/pull_request.json";
        $this->payload = json_decode(file_get_contents($filename));

        parent::setUp();
    }

    /**
     * @dataProvider payloadDescriptionProvider
     */
    public function testWhenRepositoryPerAction ($action, $description) {
        $this->payload->action = $action;
        $event = new PullRequestEvent($this->payload);
        $this->assertSame($description, $event->getDescription());
    }

    /**
     * Provides actions and descritions for testWhenRepositoryPerAction
     *
     * See https://developer.github.com/v3/activity/events/types/#pullrequestevent
     */
    public function payloadDescriptionProvider () {
        return [
            ['assigned', "baxterthehacker has assigned the pull request #1 — Update the README with new information to alken-orin"],
            ['unassigned', "baxterthehacker has edited the assignees from the pull request #1 — Update the README with new information"],
            ['labeled', "baxterthehacker has labeled the pull request #1 — Update the README with new information"],
            ['unlabeled', "baxterthehacker has removed a label from the pull request #1 — Update the README with new information"],
            ['opened', "baxterthehacker has opened a pull request: #1 — Update the README with new information"],
            ['edited', "baxterthehacker has edited the pull request #1 — Update the README with new information"],
            ['closed', "baxterthehacker has closed the pull request #1 — Update the README with new information"],
            ['reopened', "baxterthehacker has reopened the pull request #1 — Update the README with new information"],
            ['quuxed', "Unknown pull request action: quuxed"],
        ];
    }

}
