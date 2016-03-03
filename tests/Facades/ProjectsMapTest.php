<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Facades\ProjectsMap;
use Nasqueron\Notifications\Tests\TestCase;

class ProjectsMapTest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            'Nasqueron\Notifications\Phabricator\ProjectsMapFactory',
            ProjectsMap::getFacadeRoot()
        );
    }
}
