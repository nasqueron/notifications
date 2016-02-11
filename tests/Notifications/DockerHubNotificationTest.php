<?php

namespace Nasqueron\Notifications\Tests\Notifications;

use Nasqueron\Notifications\Notifications\DockerHubNotification;
use Nasqueron\Notifications\Tests\TestCase;

class DockerHubNotificationTest extends TestCase {
    /**
     * @var Nasqueron\Notifications\Notifications\DockerHubNotification
     */
    private $notification;

    /**
     * @var stdClass
     */
    private $payload;

    public function setUp () {
        $path = __DIR__ . '/../data/payloads/DockerHubPushPayload.json';
        $this->payload = json_decode(file_get_contents($path));

        $this->notification = new DockerHubNotification(
            "Acme",
            "push",
            $this->payload
        );
    }

    public function testProperties () {
        $this->assertSame("DockerHub", $this->notification->service);
        $this->assertSame("Acme", $this->notification->project);
        $this->assertSame("docker", $this->notification->group);
        $this->assertSame($this->payload, $this->notification->rawContent);
        $this->assertSame("push", $this->notification->type);
        $this->assertSame(
            "New image pushed to Docker Hub registry for svendowideit/testhook by trustedbuilder",
            $this->notification->text
        );
        $this->assertSame(
            "https://registry.hub.docker.com/u/svendowideit/testhook/",
            $this->notification->link
        );
    }
}
