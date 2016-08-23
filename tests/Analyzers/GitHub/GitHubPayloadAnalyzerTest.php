<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer;
use Nasqueron\Notifications\Tests\TestCase;

class GitHubPayloadAnalyzerTest extends TestCase {

    /**
     * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer
     */
     private $unknownEventAnalyzer;

     /**
      * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer
      */
     private $pingAnalyzer;

     /**
      * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer
      */
     private $pushAnalyzer;

     /**
      * @var Nasqueron\Notifications\Analyzers\GitHub\GitHubPayloadAnalyzer
      */
     private $pushToMappedRepositoryAnalyzer;

    /**
     * Prepares the tests
     */
     public function setUp () {
        parent::setUp();

        $this->unknownEventAnalyzer = new GitHubPayloadAnalyzer(
            "Acme", // Expected without known config file
            "quux",
            new \stdClass
        );

        $this->pingAnalyzer = new GitHubPayloadAnalyzer(
            "Nasqueron", // Expected with known config file
            "ping",
            new \stdClass
        );

        $filename = __DIR__ . "/../../data/payloads/GitHubEvents/push.json";
        $payloadRawContent = file_get_contents($filename);

        $payload = json_decode($payloadRawContent);
        $this->pushAnalyzer = new GitHubPayloadAnalyzer(
            "Nasqueron", // Expected with known config
            "push",
            $payload
        );

        $dockerPayload = json_decode($payloadRawContent);
        $dockerPayload->repository->name = "docker-someapp";
        $this->pushToMappedRepositoryAnalyzer = new GitHubPayloadAnalyzer(
            "Nasqueron", // Expected with known config
            "push",
            $dockerPayload
        );
     }

    ///
    /// Test constructor
    ///

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorThrowsAnExceptionWhenPayloadIsInvalid () {
        new GitHubPayloadAnalyzer(
            "Acme",
            "push",
            "This is not an object deserialized from JSON but a string."
        );
    }

    ///
    /// Test getConfigurationFileName
    ///

    public function testGetConfigurationFileNameWhenConfigExists () {
        $this->assertSame(
            "GitHubPayloadAnalyzer/Nasqueron.json",
            $this->pingAnalyzer->getConfigurationFileName()
        );
    }

    public function testGetConfigurationFileNameWhenConfigDoesNotExist () {
        $this->assertSame(
            "GitHubPayloadAnalyzer/default.json",
            $this->unknownEventAnalyzer->getConfigurationFileName()
        );
    }

    ///
    /// Test getItemName
    ///

    public function testGetItemNameWhenEventIsAdministrative () {
        $this->assertEmpty($this->pingAnalyzer->getItemName());
    }

    public function testGetItemNameWhenEventIsRepositoryRelative () {
        $this->assertSame("public-repo", $this->pushAnalyzer->getItemName());
    }

    ///
    /// Test getGroup
    ///

    public function testGetGroupWhenEventIsAdministrative () {
        $this->assertSame("orgz", $this->pingAnalyzer->getGroup());
    }

    public function testGetGroupOnPushToMappedRepository () {
        $this->assertSame("docker", $this->pushToMappedRepositoryAnalyzer->getGroup());

    }

    public function testGetGroupOnPushToNotMappedRepository () {
        $this->assertSame("nasqueron", $this->pushAnalyzer->getGroup());
    }

    ///
    /// Test if our fallback is correct when the GitHub event type is unknown
    ///

    public function testDescriptionContainsTypeWhenEventTypeIsUnknown () {
        $this->assertContains(
            "quux",
            $this->unknownEventAnalyzer->getDescription()
        );
    }

}
