<?php

namespace Nasqueron\Notifications\Tests;

use Keruald\Broker\BlackholeBroker;
use Nasqueron\Notifications\Features;

class PayloadFullTest extends TestCase {

    public function setUp () {
        parent::setUp();

        $this->disableBroker();
    }

    /**
     * Sends a GitHub ping payload to the application, with a valid signature
     */
    protected function sendValidTestPayload () {
        return $this->sendTestPayload('sha1=25f6cbd17ea4c6c69958b95fb88c879de4b66dcc');
    }

    /**
     * Sends a GitHub ping payload to the application, with a valid signature
     */
    protected function sendInvalidTestPayload () {
        return $this->sendTestPayload('sha1=somethingwrong');
    }

    protected function sendTestPayload ($signature) {
        $payload = file_get_contents(__DIR__ . '/../data/payloads/GitHubPingPayload.json');
        $this->sendPayload(
            '/gate/GitHub/Acme', // A gate existing in data/credentials.json
            $payload,
            'POST',
            [
                'X-Github-Event' => 'ping',
                'X-Github-Delivery' => 'e5dd9fc7-17ac-11e5-9427-73dad6b9b17c',
                'X-Hub-Signature' => $signature,
            ]
        );
        return $this;
    }

    /**
     * Tests a GitHub gate payload.
     */
    public function testPost () {
        $this->sendValidTestPayload()->seeJson([
            'gate' => 'GitHub',
            'door' => 'Acme',
            'action' => 'AMQPAction'
        ]);

        $this->assertResponseOk();
    }

    /**
     * Same than testPost, but without actions report.
     */
    public function testPostWithoutActionsReport () {
        Features::disable("ActionsReport");

        $this->sendValidTestPayload();
        $this->assertEmpty($this->response->getContent());
        $this->assertResponseOk();

        // Let's throw an Exception at broker level.
        // Without ActionsReport, the client must always receive a 200 OK.

        $this->app->instance('broker', function ($app) {
            // A non omnipotent instance, so it doesn't mock connect().
            return new BlackholeBroker;
        });
        $this->sendValidTestPayload();
        $this->assertEmpty($this->response->getContent());
        $this->assertResponseOk();
    }

    /**
     * Tests a GitHub gate payload.
     */
    public function testInvalidSignature () {
        $this->sendInvalidTestPayload()
             ->assertResponseStatus(403);
    }

    public function testBrokerIssue () {
        $this->mockNotOperationalBroker();

        $payload = file_get_contents(__DIR__ . '/../data/payloads/GitHubPingPayload.json');
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
