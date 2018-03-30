<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Console\Commands\NotificationsPayload;

class NotificationsPayloadTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\NotificationsPayload';

    public function testRegularExecute () {
        $path = __DIR__ . '/../../data/payloads/DockerHubPushPayload.json';
        $this->tester->execute([
            'command' => $this->command->getName(),
            'service' => 'DockerHub',
            'payload' => $path,
            'args' => [
                'Acme',
                'push'
            ],
        ]);

        $this->assertDisplayContains('"service": "DockerHub"');
        $this->assertDisplayContains('"project": "Acme"');
        $this->assertDisplayContains('svendowideit\/testhook');
    }

    public function testPhabricatorPayload () {
        $path = __DIR__ . '/../../data/payloads/PhabricatorPastePayload.json';
        $this->tester->execute([
            'command' => $this->command->getName(),
            'service' => 'Phabricator',
            'payload' => $path,
            'args' => [
                'Acme',
            ],
        ]);

        $this->assertDisplayContains('"service": "Phabricator"');
        $this->assertDisplayContains('"project": "Acme"');
        $this->assertDisplayContains('"type": "PSTE"');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testArgumentsArrayCombine () {
        NotificationsPayload::argumentsArrayCombine(['foo'], []);
    }

    public function testFileNotFound () {
        $this->tester->execute([
            'command' => $this->command->getName(),
            'service' => 'DockerHub',
            'payload' => "/tmp/not.found",
            'args' => [
                'Acme',
                'push'
            ],
        ]);

        $this->assertDisplayContains('File not found: /tmp/not.found');
    }

    public function testServiceNotFound () {
        $path = __DIR__ . '/../../data/payloads/DockerHubPushPayload.json';
        $this->tester->execute([
            'command' => $this->command->getName(),
            'service' => 'InterdimensionalTeleport',
            'payload' => $path,
            'args' => [
                'Acme',
                'push'
            ],
        ]);

        $this->assertDisplayContains(
            'Unknown service: InterdimensionalTeleport'
        );

    }

}
