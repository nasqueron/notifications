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
            '/gate/GitHub/Acme', // A gate existing in data/credentials.json
            $payload,
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
                'X-Hub-Signature' => 'sha1=25f6cbd17ea4c6c69958b95fb88c879de4b66dcc',
            ]
        )->seeJson([
            'gate' => 'GitHub',
            'door' => 'Acme',
            'action' => 'AMQPAction'
        ]);

        $this->assertResponseOk();
    }

    /**
     * Tests a GitHub gate payload.
     */
    public function testInvalidSignature () {
        $payload = file_get_contents(__DIR__ . '/../data/GitHubPingPayload.json');
        $this->sendPayload(
            '/gate/GitHub/Acme', // A gate existing in data/credentials.json
            $payload,
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
                'X-Hub-Signature' => 'sha1=somethingwrong',
            ]
        );
        $this->assertResponseStatus(403);
    }

    public function testBrokerIssue () {
        $this->mockNotOperationalBroker();

        $payload = file_get_contents(__DIR__ . '/../data/GitHubPingPayload.json');
        $this->sendPayload(
            '/gate/GitHub/Acme', // A gate existing in data/credentials.json
            $payload,
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
                'X-Hub-Signature' => 'sha1=25f6cbd17ea4c6c69958b95fb88c879de4b66dcc',
            ]
        )->seeJson([
            'gate' => 'GitHub',
            'door' => 'Acme',
            'action' => 'AMQPAction',
            'type' => 'RuntimeException',
        ]);

        $this->assertResponseStatus(503);
    }
}
