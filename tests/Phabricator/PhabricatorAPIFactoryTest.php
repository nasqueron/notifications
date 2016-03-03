<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorAPIFactoryTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Phabricator\ProjectsMapFactory
     */
    private $factory;

    public function setUp () {
        parent::setUp();
        $this->factory = $this->app->make('phabricator-api');
    }

    public function testGetAPI () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\PhabricatorAPI',
            $this->factory->get("https://phabricator.acme.tld")
        );
    }

    public function testGetAPIForProject () {
        $this->assertInstanceOf(
            '\Nasqueron\Notifications\Phabricator\PhabricatorAPI',
            $this->factory->getForProject("Acme")
        );
    }
}
