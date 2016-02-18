<?php

namespace Nasqueron\Notifications\Tests\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\Phabricator\ProjectGroupMapping;
use Nasqueron\Notifications\Tests\TestCase;

class ProjectGroupMappingTest extends TestCase {

    use WithConfiguration;

    /**
     * @var ProjectGroupMapping|]
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
            ProjectGroupMapping::doesProjectMatch(
                'quux*',
                'quuxians'
            )
        );

        $this->assertTrue(
            ProjectGroupMapping::doesProjectMatch(
                'quux*',
                'quux'
            )
        );

        $this->assertFalse(
            ProjectGroupMapping::doesProjectMatch(
                'foobar',
                'quux'
            )
        );

        $this->assertFalse(
            ProjectGroupMapping::doesProjectMatch(
                '',
                'quuxians'
            )
        );

        $this->assertFalse(
            ProjectGroupMapping::doesProjectMatch(
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

}
