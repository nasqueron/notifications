<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Nasqueron\Notifications\Analyzers\ItemGroupMapping;
use Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzerConfiguration;
use Nasqueron\Notifications\Tests\TestCase;

class JenkinsPayloadAnalyzerConfigurationTest extends TestCase {

    /**
     * Configuration
     *
     * @var \Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzerConfiguration
     */
     protected $configuration;

    /**
     * Prepares the test
     */
     public function setUp (): void {
        $filename = __DIR__ . '/../../data/JenkinsPayloadAnalyzer/Nasqueron.json';
        $mapper = new \JsonMapper();
        $this->configuration = $mapper->map(
            json_decode(file_get_contents($filename)),
            new JenkinsPayloadAnalyzerConfiguration('Nasqueron')
        );

        parent::setUp();
     }

    /**
     * Determines the JSON object is well parsed
     */
    public function testProperties () {
        $this->assertSame("ci", $this->configuration->defaultGroup);

        foreach ($this->configuration->map as $item) {
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
