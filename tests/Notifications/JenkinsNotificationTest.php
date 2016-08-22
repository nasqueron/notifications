<?php

namespace Nasqueron\Notifications\Tests\Notifications;

use Nasqueron\Notifications\Notifications\JenkinsNotification;
use Nasqueron\Notifications\Tests\TestCase;

class JenkinsNotificationTest extends TestCase {
    /**
     * @var Nasqueron\Notifications\Notifications\JenkinsNotification
     */
    private $notification;

    /**
     * @var stdClass
     */
    private $payload;

    public function prepareNotification ($payloadFile, $project = "Acme") {
        $path = __DIR__ . '/../data/payloads/' . $payloadFile;
        $this->payload = json_decode(file_get_contents($path));

        $this->notification = new JenkinsNotification(
            $project,
            $this->payload
        );
    }

    public function testProperties () {
        $this->prepareNotification('JenkinsPayload.json');

        $this->assertSame("Jenkins", $this->notification->service);
        $this->assertSame("Acme", $this->notification->project);
        $this->assertSame("ci", $this->notification->group);
        $this->assertSame($this->payload, $this->notification->rawContent);
        $this->assertSame("completed.success", $this->notification->type);
        $this->assertSame(
            "Jenkins job asgard has been completed: success",
            $this->notification->text
        );
        $this->assertSame(
            "http://localhost:8080/job/asgard/18/",
            $this->notification->link
        );
    }

    public function testPropertiesForIncompletePayload () {
        $this->prepareNotification('JenkinsStartedPayload.json');

        $this->assertSame("started", $this->notification->type);
        $this->assertSame(
            "Jenkins job asgard has been started",
            $this->notification->text
        );
    }

    public function testShouldNotifyOnDefaultConfiguration () {
        $this->prepareNotification('JenkinsPayload.json');
        $this->assertTrue($this->notification->shouldNotify());
    }

    public function testShouldNotifyWhenConfiguredNotTo () {
        $this->prepareNotification('JenkinsToIgnorePayload.json', 'Nasqueron');
        $this->assertFalse($this->notification->shouldNotify());
    }
}
