<?php

namespace Nasqueron\Notifications\Tests\Analyzers\GitHub\Events;

use Nasqueron\Notifications\Analyzers\GitHub\Events\PushEvent;
use Nasqueron\Notifications\Tests\TestCase;

class PushEventTest extends TestCase {

    /**
     * @var stdClass[]
     */
    private $payloads;

    public function setUp () {
        $payloadsToPrepare = [
            '0' => 'GitHubPushForceZeroPayload.json',
            '1' => 'GitHubEvents/push.json',
            'n' => 'GitHubPushSeveralCommitsPayload.json',
        ];

        foreach ($payloadsToPrepare as $key => $filename) {
            $filename = __DIR__ . "/../../../data/payloads/" . $filename;
            $this->payloads[$key] = json_decode(file_get_contents($filename));
        }

        parent::setUp();
    }

    ///
    /// WithRepoAndBranch trait
    ///


    public function testGetRepositoryAndBranch () {
        $this->assertEquals("", PushEvent::getRepositoryAndBranch("", "master"));
        $this->assertEquals("", PushEvent::getRepositoryAndBranch("", "foo"));
        $this->assertEquals("quux", PushEvent::getRepositoryAndBranch("quux", "master"));
        $this->assertEquals("quux", PushEvent::getRepositoryAndBranch("quux", "refs/heads/master"));
        $this->assertEquals("quux", PushEvent::getRepositoryAndBranch("quux", ""));
        $this->assertEquals("quux (branch foo)", PushEvent::getRepositoryAndBranch("quux", "refs/heads/foo"));
        $this->assertEquals("quux (branch feature/foo)", PushEvent::getRepositoryAndBranch("quux", "refs/heads/feature/foo"));
        $this->assertEquals("quux (branch feature/foo)", PushEvent::getRepositoryAndBranch("quux", "feature/foo"));
        $this->assertEquals("quux (branch foo)", PushEvent::getRepositoryAndBranch("quux", "foo"));
        $this->assertEquals("quux (branch 0)", PushEvent::getRepositoryAndBranch("quux", "0"));
    }

    ///
    /// WithCommit trait
    ///

    public function testGetCommitTitle () {
        $this->assertEquals("", PushEvent::getCommitTitle(""));
        $this->assertEquals("Lorem ipsum dolor", PushEvent::getCommitTitle("Lorem ipsum dolor"));

        $longCommitMessages = [
            "I was born in a water moon. Some people, especially its inhabitants, called it a planet, but as it was only a little over two hundred kilometres in diameter, 'moon' seems the more accurate term. The moon was made entirely of water, by which I mean it was a globe that not only had no land, but no rock either, a sphere with no solid core at all, just liquid water, all the way down to the very centre of the globe.",
            "I was born in a water moon. Some people, especially its inhabitants, called it a planet, but as it was only a little over two hundred kilometres in diameter, 'moon' seems the more accurate term. The moon was made entirely of water, by which I mean it was a globe that not only had no land, but no rock either, a sphere with no solid core at all, just liquid water, all the way down to the very centre of the globe.\n\nIf it had been much bigger the moon would have had a core of ice, for water, though supposedly incompressible, is not entirely so, and will change under extremes of pressure to become ice. (If you are used to living on a planet where ice floats on the surface of water, this seems odd and even wrong, but nevertheless it is the case.) The moon was not quite of a size for an ice core to form, and therefore one could, if one was sufficiently hardy, and adequately proof against the water pressure, make one's way down, through the increasing weight of water above, to the very centre of the moon.",
        ];
        $shortCommitTitle = "I was born in a water moon. Some people, especially its inhabitants, ca…";
        foreach ($longCommitMessages as $longCommitMessage) {
            $this->assertEquals(
                $shortCommitTitle,
                PushEvent::getCommitTitle($longCommitMessage)
            );
        }
    }

    public function testWhenTheCommitterAndAuthorAreDifferent () {
        $payload = clone $this->payloads['1'];
        $payload->head_commit->author->username = "Skrunge";
        $event = new PushEvent($payload);

        $this->assertSame(
            "baxterthehacker committed Update README.md (authored by Skrunge)",
            $event->getDescription()
        );
    }

    public function testOnGitPushForce () {
        $event = new PushEvent($this->payloads['0']);

        $this->assertSame(
            "dereckson forcely updated docker-nginx-php-fpm (branch novolume)",
            $event->getDescription()
        );
        $this->assertContains("compare", $event->getLink());
    }

    public function testOnGitPushWithSeveralCommits () {
        $event = new PushEvent($this->payloads['n']);

        $this->assertSame(
            "dereckson pushed 2 commits to notifications",
            $event->getDescription()
        );
        $this->assertContains("compare", $event->getLink());
    }
}
