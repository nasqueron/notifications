<?php

namespace Nasqueron\Notifications\Tests\Http\Controllers;

use Nasqueron\Notifications\Tests\TestCase;

class GitHubGateControllerTest extends TestCase {
    public function setUp () {
        parent::setUp();

        $this->disableEvents();
    }

    /**
     * GitHub gate works.
     *
     * @return void
     */
    public function testGet () {
        $this->visit('/gate/GitHub')
             ->see('POST');
    }

    /**
     * Tests a GitHub gate payload.
     */
    public function testPost () {
        $payload = file_get_contents(__DIR__ . '/../../data/GitHubPingPayload.json');
        $this->sendPayload(
            '/gate/GitHub/Quux',
            $payload,
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c'
            ]
        )
        ->seeJson([
            'gate' => 'GitHub',
            'door' => 'Quux',
            'actions' => []
        ]);

        $this->assertResponseOk();
    }
}
