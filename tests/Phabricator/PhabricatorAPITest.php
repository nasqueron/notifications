<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Phabricator\PhabricatorAPI;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorAPITest extends TestCase {
    public function testForInstance () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\PhabricatorAPI',
            PhabricatorAPI::forInstance("https://phabricator.acme.tld")
        );
    }

    public function testForProject () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\PhabricatorAPI',
            PhabricatorAPI::forInstance("https://phabricator.acme.tld")
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testForInstanceWhere () {
        PhabricatorAPI::forInstance("https://notfound.acme.tld");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testForProjectWhenProjectDoesNotExist () {
        PhabricatorAPI::forProject("NotFound");
    }
}
