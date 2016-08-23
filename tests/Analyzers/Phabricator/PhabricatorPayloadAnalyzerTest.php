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

    public function testGetConfigurationFileName () {
        $this->assertSame(
            "PhabricatorPayloadAnalyzer/Nasqueron.json",
            $this->analyzer->getConfigurationFileName()
        );
    }

    public function testGetGroupWhereEventIsAdministrative  () {
        $this->markTestIncomplete(
            "Not yet implemented feature. See T664."
        );

        $this->assertSame(
            "orgz",
            $this->analyzer->getGroup()
        );
    }

    public function testGetGroupWhereStoryDoesntMatchAnything () {
        $this->attachProjectsToStoryMock($this->story, []);
        $this->assertSame(
            "nasqueron",
            $this->analyzer->getGroup()
        );
    }

    public function testGetGroupWhereStoryMatchesProject () {
        $this->attachProjectsToStoryMock($this->story, ['Docker images']);
        $this->assertSame(
            "docker",
            $this->analyzer->getGroup()
        );
    }

    public function testGetGroupWhereStoryMatchesWords () {
        $this->attachProjectsToStoryMock($this->story, []);
        $this->story->text = "Review the cartography elements.";
        $this->assertSame(
            "tasacora",
            $this->analyzer->getGroup()
        );
    }

    public function testGetGroupWhereWordsAreStrong () {
        $this->markTestIncomplete(
            "Not yet implemented feature. See T748."
        );

        $this->attachProjectsToStoryMock($this->story, ['Docker images']);
        $this->story->text = "Review the cartography elements on Dwellers.";

        $this->assertSame(
            "ops",
            $this->analyzer->getGroup()
        );
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetItemThrowsBadMethodCallException () {
        $this->analyzer->getItemName();
    }

}
