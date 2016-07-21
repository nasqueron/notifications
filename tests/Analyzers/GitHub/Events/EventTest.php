<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\Event;
use Nasqueron\Notifications\Tests\TestCase;

class EventTest extends TestCase {

    public function testGetClass () {
        $this->assertSame(
            'Nasqueron\Notifications\Analyzers\GitHub\Events\CommitCommentEvent',
            Event::getClass('commit_comment')
        );
    }

    public function testForPayload () {
        $this->assertInstanceOf(
            'Nasqueron\Notifications\Analyzers\GitHub\Events\CommitCommentEvent',
            Event::forPayload('commit_comment', new \stdClass)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testForPayloadWithException () {
        Event::forPayload('not_existing', new \stdClass);
    }

    public function testCut () {
        $this->assertSame('', Event::cut(''));
        $this->assertSame('', Event::cut('', 0));
        $this->assertSame('…', Event::cut('Lorem ipsum dolor', 0));
        $this->assertSame('Lorem…', Event::cut('Lorem ipsum dolor', 6));
        $this->assertSame('Lorem ipsum dolor', Event::cut('Lorem ipsum dolor'));
    }

    /**
     * @dataProvider payloadDescriptionProvider
     */
    public function testGetDescriptionAndLink ($eventName,
                                               $expectedDescription,
                                               $expectedLink) {
        $filename = __DIR__ . "/../../../data/payloads/GitHubEvents/$eventName.json";
        $payload = json_decode(file_get_contents($filename));
        $event = Event::forPayload($eventName, $payload);

        $this->assertSame($expectedDescription, $event->getDescription());
        $this->assertSame($expectedLink, $event->getLink());
    }

    public function payloadDescriptionProvider () {
        return [
            'CommitCommentEvent' => [
                'commit_comment',
                'baxterthehacker added a comment to 9049f126: This is a really good change! :+1:',
                'https://github.com/baxterthehacker/public-repo/commit/9049f1265b7d61be4a8904a9a27120d2064dab3b#commitcomment-11056394'
            ],
            'CreateEvent' => [
                'create',
                'New tag on baxterthehacker/public-repo: 0.0.1',
                'https://github.com/baxterthehacker/public-repo/releases/tag/0.0.1'
            ],
            'DeleteEvent' => [
                'delete',
                'Removed tag on baxterthehacker/public-repo: simple-tag',
                'https://github.com/baxterthehacker/public-repo/tags'
            ],
            'ForkEvent' => [
                'fork',
                'baxterthehacker/public-repo has been forked to baxterandthehackers/public-repo',
                'https://github.com/baxterandthehackers/public-repo'
            ],
            'PushEvent' => [
                'push',
                'baxterthehacker committed Update README.md',
                'https://github.com/baxterthehacker/public-repo/commit/0d1a26e67d8f5eaf1f6ba5c57fc3c7d91ac0fd1c'
            ],
            'RepositoryEvent' => [
                'repository',
                'New repository baxterandthehackers/new-repository',
                'https://github.com/baxterandthehackers/new-repository'
            ],
            'StatusEvent' => [
                'status',
                'Status of 9049f126: default — success',
                ''
            ],
            'WatchEvent' => [
                'watch',
                'baxterthehacker starred baxterthehacker/public-repo',
                'https://github.com/baxterthehacker'
            ],
        ];
    }

}
