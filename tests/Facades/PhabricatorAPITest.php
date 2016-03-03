<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Facades\PhabricatorAPI;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorAPITest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            'Nasqueron\Notifications\Phabricator\PhabricatorAPIFactory',
            PhabricatorAPI::getFacadeRoot()
        );
    }
}
