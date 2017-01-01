<?php

namespace Nasqueron\Notifications\Tests\Config\Reporting;

use Nasqueron\Notifications\Tests\TestCase;

class IntegrationTest extends TestCase {

    public function setUp () {
        parent::setUp();

        $this->mockServices()
            ->shouldReceive('get')
            ->once()
            ->andReturn([]); // No service
    }

    /**
     * Config works.
     */
    public function testConfig() {
        $json = $this->get('/config')
                    ->response
                    ->getContent();

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . "/../../data/config.json",
            $json
        );
    }

}
