<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Tests\TestCase;

class ProjectsMapFactoryTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Phabricator\ProjectsMapFactory
     */
    private $factory;

    public function setUp () {
        parent::setUp();
        $this->factory = $this->app->make('phabricator-projectsmap');

        $mock = $this->mockPhabricatorAPIForProjectsMap();
    }

    public function testLoadProjectsMap () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\ProjectsMap',
            $this->factory->load("https://phabricator.acme.tld")
        );
    }

    public function testFetchProjectsMap () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\ProjectsMap',
            $this->factory->fetch("https://phabricator.acme.tld")
        );
    }

}
