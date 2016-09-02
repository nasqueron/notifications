<?php

namespace Nasqueron\Notifications\Tests\Notifications;

use Nasqueron\Notifications\Notifications\DockerHubNotification;
use Nasqueron\Notifications\Tests\TestCase;

use Keruald\Mailgun\Tests\WithMockHttpClient;

use Mockery;

class DockerHubNotificationTest extends TestCase {

    use WithMockHttpClient;

    /**
     * @return Nasqueron\Notifications\Notifications\DockerHubNotification
     */
    protected function prepareNotification ($event) {
        $path = __DIR__ . '/../data/payloads/DockerHub' . ucfirst($event)
              . 'Payload.json';
        $payload = json_decode(file_get_contents($path));

        return new DockerHubNotification("Acme", $event, $payload);
    }

    public function testPropertiesForPush () {
        $notification = $this->prepareNotification("push");

        $this->assertSame("DockerHub", $notification->service);
        $this->assertSame("Acme", $notification->project);
        $this->assertSame("docker", $notification->group);
        $this->assertSame("push", $notification->type);
        $this->assertSame(
            "New image pushed to Docker Hub registry for svendowideit/testhook by trustedbuilder",
            $notification->text
        );
        $this->assertSame(
            "https://registry.hub.docker.com/u/svendowideit/testhook/",
            $notification->link
        );
    }

    public function testPropertiesForBuildFailure () {
        $this->mockMailgunServiceProvider();
        $notification = $this->prepareNotification("buildFailure");
        $this->assertSame("buildFailure", $notification->type);

        $this->assertSame(
            "Image build by Docker Hub registry failure for acme/foo",
            $notification->text
        );
        $this->assertSame(
            "https://hub.docker.com/r/acme/foo/builds/abcdef123456/",
            $notification->link
        );
    }

    ///
    /// Helper mock method
    ///

    /**
     * Injects into our container a mock of MailgunMessageFactory
     */
    protected function mockMailgunServiceProvider () {
        $mock = Mockery::mock('Keruald\Mailgun\MailgunMessageFactory');
        $payload = $this->mockMailgunResponse();
        $mock->shouldReceive('fetchMessageFromPayload')->once()->andReturn($payload);
        $this->app->instance('mailgun', $mock);
    }

    /**
     * @return stdClass
     */
    protected function mockMailgunResponse () {
        return json_decode($this->mockHttpClientResponseBody());
    }

}
