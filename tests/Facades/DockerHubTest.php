<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Tests\TestCase;

use Config;
use DockerHub;

use Keruald\DockerHub\Build\TriggerBuildFactory;

class DockerHubTest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            TriggerBuildFactory::class,
            DockerHub::getFacadeRoot()
        );
    }

}
