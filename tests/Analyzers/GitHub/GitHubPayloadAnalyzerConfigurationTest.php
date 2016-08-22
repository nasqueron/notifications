<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzerConfiguration;
use Nasqueron\Notifications\Analyzers\ItemGroupMapping;
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
            new GitHubPayloadAnalyzerConfiguration('Nasqueron')
        );

        parent::setUp();
     }

    /**
     * Determines the JSON object is well parsed
     */
    public function testProperties () {
        $this->assertSame("orgz", $this->configuration->administrativeGroup);
        $this->assertSame("nasqueron", $this->configuration->defaultGroup);

        foreach ($this->configuration->repositoryMapping as $item) {
            $this->assertInstanceOf(ItemGroupMapping::class, $item);
        }
    }

    ///
    /// Tests for getDefaultGroup
    ///

    public function testGetDefaultGroup () {
        $this->configuration->defaultGroup = "quux";
        $this->assertSame("quux", $this->configuration->getDefaultGroup());
    }

    public function testGetDefaultGroupWhenNotInConfig () {
        $this->configuration->defaultGroup = "";
        $this->assertSame("nasqueron", $this->configuration->getDefaultGroup());

        $this->configuration->defaultGroup = null;
        $this->assertSame("nasqueron", $this->configuration->getDefaultGroup());
    }

}
