<?php

namespace Nasqueron\Notifications\Tests\Providers;

class PhabricatorProjectsMapServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Nasqueron\Notifications\Phabricator\ProjectsMapFactory',
            'phabricator-projectsmap'
        );
    }

}
