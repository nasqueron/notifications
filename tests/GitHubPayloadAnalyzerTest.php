<?php

namespace Nasqueron\Notifications\Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\GitHubPayloadAnalyzer;
use Nasqueron\Notifications\Analyzers\GitHubPayloadAnalyzerConfiguration;

class GitHubPayloadAnalyzerConfigurationTest extends TestCase {

    /**
     * Configuration
     *
     * @var Nasqueron\Notifications\Analyzers\GitHubPayloadAnalyzerConfiguration
     */
     protected $configuration;

    /**
     * Prepares the test
     */
     public function setUp () {
        $filename = __DIR__ . '/data/GitHubPayloadAnalyzer-Nasqueron.json';
        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(file_get_contents($filename)),
            new GitHubPayloadAnalyzerConfiguration()
        );
     }

    /**
     * Determines the JSON object is well parsed
     */
    public function testProperties () {
        $this->assertEquals("orgz", $this->configuration->administrativeGroup);
        $this->assertEquals("nasqueron", $this->configuration->defaultGroup);

        foreach ($this->configuration->repositoryMapping as $item) {
            $this->assertInstanceOf(
                'Nasqueron\Notifications\Analyzers\RepositoryGroupMapping',
                $item
            );
        }
    }

    public function testGetCommitTitle () {
        $this->assertEquals("", GitHubPayloadAnalyzer::getCommitTitle(""));
        $this->assertEquals("Lorem ipsum dolor", GitHubPayloadAnalyzer::getCommitTitle("Lorem ipsum dolor"));

        $longCommitMessages = [
            "I was born in a water moon. Some people, especially its inhabitants, called it a planet, but as it was only a little over two hundred kilometres in diameter, 'moon' seems the more accurate term. The moon was made entirely of water, by which I mean it was a globe that not only had no land, but no rock either, a sphere with no solid core at all, just liquid water, all the way down to the very centre of the globe.",
            "I was born in a water moon. Some people, especially its inhabitants, called it a planet, but as it was only a little over two hundred kilometres in diameter, 'moon' seems the more accurate term. The moon was made entirely of water, by which I mean it was a globe that not only had no land, but no rock either, a sphere with no solid core at all, just liquid water, all the way down to the very centre of the globe.\n\nIf it had been much bigger the moon would have had a core of ice, for water, though supposedly incompressible, is not entirely so, and will change under extremes of pressure to become ice. (If you are used to living on a planet where ice floats on the surface of water, this seems odd and even wrong, but nevertheless it is the case.) The moon was not quite of a size for an ice core to form, and therefore one could, if one was sufficiently hardy, and adequately proof against the water pressure, make one's way down, through the increasing weight of water above, to the very centre of the moon.",
        ];
        $shortCommitTitle = "I was born in a water moon. Some people, especially its inhabitants, ca…";
        foreach ($longCommitMessages as $longCommitMessage) {
            $this->assertEquals(
                $shortCommitTitle,
                GitHubPayloadAnalyzer::getCommitTitle($longCommitMessage)
            );
        }
    }

    public function testGetRepositoryAndBranch () {
        $this->assertEquals("", GitHubPayloadAnalyzer::getRepositoryAndBranch("", "master"));
        $this->assertEquals("", GitHubPayloadAnalyzer::getRepositoryAndBranch("", "foo"));
        $this->assertEquals("quux", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "master"));
        $this->assertEquals("quux", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "refs/heads/master"));
        $this->assertEquals("quux", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", ""));
        $this->assertEquals("quux (branch foo)", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "refs/heads/foo"));
        $this->assertEquals("quux (branch feature/foo)", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "refs/heads/feature/foo"));
        $this->assertEquals("quux (branch feature/foo)", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "feature/foo"));
        $this->assertEquals("quux (branch foo)", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "foo"));
        $this->assertEquals("quux (branch 0)", GitHubPayloadAnalyzer::getRepositoryAndBranch("quux", "0"));
    }
}
