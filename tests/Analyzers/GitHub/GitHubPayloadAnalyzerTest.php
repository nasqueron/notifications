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
     * Prepares the tests
     */
     public function setUp () {
        parent::setUp();

        $this->unknownEventAnalyzer = new GitHubPayloadAnalyzer(
            "Acme",
            "quux",
            new \stdClass
        );
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
