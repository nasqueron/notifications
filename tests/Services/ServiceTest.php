<?php

namespace Nasqueron\Notifications\Tests\Services;

use Nasqueron\Notifications\Config\Services\Service;
use Nasqueron\Notifications\Tests\TestCase;

class ServiceTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Config\Services\Service
     */
    private $serviceWithInstance;

    /**
     * @var \Nasqueron\Notifications\Config\Services\Service
     */
    private $serviceWithoutInstance;

    public function setUp () {
        $this->serviceWithoutInstance = new Service();

        $this->serviceWithInstance = clone $this->serviceWithoutInstance;
        $this->serviceWithInstance->instance = "http://www.perdu.com";
    }

    ///
    /// Tests for getInstanceName()
    ///

    public function testGetInstanceName () {
        $this->assertSame(
            "http://www.perdu.com",
            $this->serviceWithInstance->getInstanceName()
        );
    }

    public function testGetInstanceNameWhenThereIsNoInstance () {
        $this->assertSame(
            "ø",
            $this->serviceWithoutInstance->getInstanceName()
        );
    }

}
