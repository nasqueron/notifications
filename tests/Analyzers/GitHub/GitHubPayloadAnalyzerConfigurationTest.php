<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzerConfiguration;
use Nasqueron\Notifications\Tests\TestCase;

class GitHubPayloadAnalyzerConfigurationTest extends TestCase {

    /**
     * Configuration
     *
     * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzerConfiguration
     */
     protected $configuration;

    /**
     * Prepares the test
     */
     public function setUp () {
        $filename = __DIR__ . '/../../data/GitHubPayloadAnalyzer/Nasqueron.json';
        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(file_get_contents($filename)),
            new GitHubPayloadAnalyzerConfiguration()
        );

        parent::setUp();
     }

    /**
     * Determines the JSON object is well parsed
     */
    public function testProperties () {
        $this->assertEquals("orgz", $this->configuration->administrativeGroup);
        $this->assertEquals("nasqueron", $this->configuration->defaultGroup);

        foreach ($this->configuration->repositoryMapping as $item) {
            $this->assertInstanceOf(
                'Nasqueron\Notifications\Analyzers\GitHub\RepositoryGroupMapping',
                $item
            );
        }
    }
}
