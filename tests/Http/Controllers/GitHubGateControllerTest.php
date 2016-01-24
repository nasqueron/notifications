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
        $payload = file_get_contents(__DIR__ . '/../../data/payloads/GitHubPingPayload.json');
        $this->sendPayload(
            '/gate/GitHub/Quux', // A gate not existing in data/credentials.json
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

    /**
     * Tests a malformed GitHub gate payload.
     */
    public function testMalformedPost () {
        $this->sendPayload(
            '/gate/GitHub/Quux', // A gate not existing in data/credentials.json
            "",
            'POST',
            [
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
            ]
        );
        $this->assertResponseStatus(400);

        $this->sendPayload(
            '/gate/GitHub/Quux', // A gate not existing in data/credentials.json
            "",
            'POST',
            [
                'X-Github-Event' => 'ping',
            ]
        );
        $this->assertResponseStatus(400);

        $this->sendPayload(
            '/gate/GitHub/Quux', // A gate not existing in data/credentials.json
            "",
            'POST',
            [
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
                'X-Github-Event' => 'ping',
            ]
        );
        $this->assertResponseStatus(400);
    }

    public function testEmptySignature () {
        $this->sendPayload(
            '/gate/GitHub/Acme', // A gate existing in data/credentials.json
            "",
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
            ]
        );
        $this->assertResponseStatus(403);
    }
}
