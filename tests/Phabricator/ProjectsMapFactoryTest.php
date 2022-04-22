<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Tests\TestCase;

class ProjectsMapFactoryTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Phabricator\ProjectsMapFactory
     */
    private $factory;

    public function setUp (): void {
        parent::setUp();
        $this->factory = $this->app->make('phabricator-projectsmap');

        $this->mockPhabricatorAPIForProjectsMap();
    }

    public function testLoadProjectsMap () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\ProjectsMap',
            $this->factory->load("Acme")
        );
    }

    public function testFetchProjectsMap () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\ProjectsMap',
            $this->factory->fetch("Acme")
        );
    }

}
