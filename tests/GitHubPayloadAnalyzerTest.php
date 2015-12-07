<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

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
}
