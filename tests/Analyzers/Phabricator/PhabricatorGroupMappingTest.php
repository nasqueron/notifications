<?php

namespace Nasqueron\Notifications\Tests\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorGroupMapping;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorGroupMappingTest extends TestCase {

    use WithConfiguration;

    /**
     * @var PhabricatorGroupMapping|]
     */
    private $mappings;

    /**
     * @var PhabricatorStory
     */
    private $story;

    public function setUp () {
        parent::setUp();

        $config = $this->getPhabricatorPayloadAnalyzerConfiguration();

        $keys = [
            'projects',
            'words',
            'strongWords',
        ];

        $this->mappings = array_combine($keys, $config->groupsMapping);

        $this->story = $this->getStory();
    }

    ///
    /// Tests
    ///

    public function testDoesProjectMatch () {
        $this->assertTrue(
            PhabricatorGroupMapping::doesProjectMatch(
                'quux*',
                'quuxians'
            )
        );

        $this->assertTrue(
            PhabricatorGroupMapping::doesProjectMatch(
                'quux*',
                'quux'
            )
        );

        $this->assertFalse(
            PhabricatorGroupMapping::doesProjectMatch(
                'foobar',
                'quux'
            )
        );

        $this->assertFalse(
            PhabricatorGroupMapping::doesProjectMatch(
                '',
                'quuxians'
            )
        );

        $this->assertFalse(
            PhabricatorGroupMapping::doesProjectMatch(
                'quux*',
                ''
            )
        );
    }

    public function testDoesProjectBelong () {
        $mapping = $this->mappings['projects'];
        $this->assertFalse(
            $mapping->doesProjectBelong("")
        );

        $this->assertFalse(
            $mapping->doesProjectBelong("Tasacora")
        );

        $this->assertTrue(
            $mapping->doesProjectBelong("Docker images")
        );

        $this->assertFalse(
            $mapping->doesProjectBelong("Docker")
        );

        $this->assertFalse(
            $mapping->doesProjectBelong("Docker images quux")
        );
    }

    public function testDoesStoryBelong () {
        $mapping = $this->mappings['words'];

        $this->assertFalse(
            $mapping->doesStoryBelong($this->story)
        );

        $this->story->text = "Review the cartography elements.";
        $this->assertTrue(
            $mapping->doesStoryBelong($this->story)
        );
    }

    /**
     * Test to fix T773
     */
    public function testDoesStoryBelongWhenWordIsInAnotherCase () {
        $mapping = $this->mappings['words'];

        $this->story->text = "Review the Cartography elements.";
        $this->assertTrue(
            $mapping->doesStoryBelong($this->story)
        );
    }

}
