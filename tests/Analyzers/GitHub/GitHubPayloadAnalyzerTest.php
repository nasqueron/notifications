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
        $payload = json_decode(file_get_contents($filename));
        $this->pushAnalyzer = new GitHubPayloadAnalyzer(
            "Nasqueron", // Expected with known config
            "push",
            $payload
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
    /// Test getRepository
    ///

    public function testGetRepositoryWhenEventIsAdministrative () {
        $this->assertEmpty($this->pingAnalyzer->getRepository());
    }

    public function testGetRepositoryWhenEventIsRepositoryRelative () {
        $this->assertSame("public-repo", $this->pushAnalyzer->getRepository());
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
