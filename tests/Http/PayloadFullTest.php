<?php

namespace Nasqueron\Notifications\Tests;

class PayloadFullTest extends TestCase {

    public function setUp () {
        parent::setUp();

        $this->disableBroker();
    }

    /**
     * Tests a GitHub gate payload.
     */
    public function testPost () {
        $payload = file_get_contents(__DIR__ . '/../data/GitHubPingPayload.json');
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
            'action' => 'AMQPAction'
        ]);

        $this->assertResponseOk();
    }
}
