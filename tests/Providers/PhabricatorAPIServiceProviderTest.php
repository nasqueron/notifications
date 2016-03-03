<?php

namespace Nasqueron\Notifications\Tests\Providers;

class PhabricatorAPIServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory',
            'phabricator-api'
        );
    }

}
