<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\IssueCommentEvent;
use Nasqueron\Notifications\Tests\TestCase;

class IssueCommentEventTest extends TestCase {

    /**
     * @var stdClass
     */
    private $payload;

    public function setUp () {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/issue_comment.json";
        $this->payload = json_decode(file_get_contents($filename));

        parent::setUp();
    }

    /**
     * @dataProvider payloadDescriptionProvider
     */
    public function testWhenRepositoryPerAction ($action, $description) {
        $this->payload->action = $action;
        $event = new IssueCommentEvent($this->payload);
        $this->assertSame($description, $event->getDescription());
    }

    /**
     * Provides actions and descritions for testWhenRepositoryPerAction
     *
     * See https://developer.github.com/v3/activity/events/types/#issuecommentevent
     */
    public function payloadDescriptionProvider () {
        return [
            ['created', "baxterthehacker added a comment to issue #2 — Spelling error in the README file: You are totally right! I'll get this fixed right away."],
            ['edited', "baxterthehacker edited a comment to issue #2 — Spelling error in the README file: You are totally right! I'll get this fixed right away."],
            ['deleted', "baxterthehacker deleted a comment to issue #2 — Spelling error in the README file"],
        ];
    }

}
