<?php

namespace Nasqueron\Notifications\Tests\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorPayloadAnalyzer;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorPayloadAnalyzerTest extends TestCase {

    use WithConfiguration;

    /**
     * @var PhabricatorPayloadAnalyzer
     */
    private $analyzer;

    /**
     * @var PhabricatorStory
     */
    private $story;

    public function setUp () {
        parent::setUp();

        $this->story = $this->getStory();
        $this->analyzer = new PhabricatorPayloadAnalyzer(
            "Nasqueron",
            $this->story
        );
    }

    public function testGetGroupWhereWordsAreStrong () {
        $this->markTestIncomplete(
            "Not yet implemented feature. See D748."
        );

        $this->story->projects = ['Docker images'];
        $this->story->text = "Review the cartography elements on Dwellers.";

        $this->assertSame(
            "ops",
            $this->analyzer->getGroup()
        );
    }

}
