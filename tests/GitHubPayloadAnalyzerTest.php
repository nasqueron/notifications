<?php

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
            "I was born in a water moon. Some people, especially its inhabitants, called it a planet, but as it was only a little over two hundred kilometres in diameter, 'moon' seems the more accurate term. The moon was made entirely of water, by which I mean it was a globe that not only had no land, but no rock either, a sphere with no solid core at all, just liquid water, all the way down to the very centre of the globe."
        ];
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
