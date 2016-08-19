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

        $this->assertContains('"service": "DockerHub"', $this->tester->getDisplay());
        $this->assertContains('"project": "Acme"', $this->tester->getDisplay());
        $this->assertContains('svendowideit\/testhook', $this->tester->getDisplay());
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

        $this->assertContains(
            'File not found: /tmp/not.found',
            $this->tester->getDisplay()
        );

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

        $this->assertContains(
            'Unknown service: InterdimensionalTeleport',
            $this->tester->getDisplay()
        );

    }

}
