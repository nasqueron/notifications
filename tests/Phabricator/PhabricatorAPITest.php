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

    public function testForInstanceWhere () {
        $this->expectException(\RuntimeException::class);
        PhabricatorAPI::forInstance("https://notfound.acme.tld");
    }

    public function testForProjectWhenProjectDoesNotExist () {
        $this->expectException(\RuntimeException::class);
        PhabricatorAPI::forProject("NotFound");
    }

    public function testGetFirstResultForObject () {
        $result = new \stdClass;
        $result->data = ["first", "second", "third"];

        $this->assertEquals("first", PhabricatorAPI::getFirstResult($result));
    }

    public function testGetFirstResultForIterable () {
        $result = ["first", "second", "third"];

        $this->assertEquals("first", PhabricatorAPI::getFirstResult($result));
    }

}
