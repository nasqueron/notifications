<?php

namespace Nasqueron\Notifications\Tests\Services;

use Nasqueron\Notifications\Services\Services;
use Nasqueron\Notifications\Tests\TestCase;

class ServicesTest extends TestCase {

    private $services;

    public function setUp () {
        parent::setUp();

        $this->services = Services::loadFromJson('credentials.json');
    }

    public function testGet () {
        $actualServices = $this->services->get();

        $this->assertGreaterThan(0, $actualServices);

        $this->assertSame(
            $this->services->services, // This is public, so testable
            $actualServices
        );

        foreach ($actualServices as $service) {
            $this->assertInstanceOf(
                'Nasqueron\Notifications\Services\Service',
                $service
            );
        }
    }

    public function testGetForGate () {
        $actualServices = $this->services->getForGate('GitHub');
        $this->assertGreaterThan(0, $actualServices);
        foreach ($actualServices as $service) {
            $this->assertInstanceOf(
                'Nasqueron\Notifications\Services\Service',
                $service
            );
            $this->assertSame('GitHub', $service->gate);
        }
    }

    public function testFindServiceByDoor () {
        // Search gives a result

        $service = $this->services->findServiceByDoor('GitHub', 'Acme');
        $this->assertInstanceOf(
                'Nasqueron\Notifications\Services\Service',
                $service
        );
        $this->assertSame('GitHub', $service->gate);
        $this->assertSame('Acme', $service->door);

        // Search doesn't give any result

        $service = $this->services->findServiceByDoor('GitHub', 'Quux');
        $this->assertNull($service);
    }

    public function testFindServiceByProperty () {
        // Search gives a result

        $service = $this->services->findServiceByProperty(
            'Phabricator',
            'instance',
            'https://phabricator.acme.tld'
        );
        $this->assertInstanceOf(
                'Nasqueron\Notifications\Services\Service',
                $service
        );
        $this->assertSame('Phabricator', $service->gate);
        $this->assertSame('Acme', $service->door);

        // Search doesn't give any result

        $service = $this->services->findServiceByProperty(
            'Phabricator',
            'instance',
            'https://notfound.acme.tld'
        );
        $this->assertNull($service);
    }

}
