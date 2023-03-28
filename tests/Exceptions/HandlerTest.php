<?php

namespace Nasqueron\Notifications\Tests\Exceptions;

use Nasqueron\Notifications\Exceptions\Handler;
use Nasqueron\Notifications\Tests\TestCase;

use Sentry\State\HubInterface;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Config;
use Mockery;

class HandlerTest extends TestCase {

    /**
     * Illuminate\Foundation\Exceptions\Handler
     */
    private $handler;

    /**
     * Sentry client
     */
    private HubInterface $sentryClientMock;

    public function setUp (): void {
        parent::setUp();

        $this->handler = new Handler($this->app);

        $this->mockSentryClient();
    }

    protected function mockSentryClient () {
        // Inject into our container a mock of Raven_Client
        $this->sentryClientMock = Mockery::mock(HubInterface::class);
        $this->app->instance('sentry', $this->sentryClientMock);

        // Environment shouldn't be 'testing' and DSN should be defined,
        // so Handler::report will call Sentry to report to Sentry
        Config::set('app.env', 'testing-raven');
        Config::set('services.sentry.dsn', 'mock');
    }

    public function tesSentryReport () {
        $this->sentryClientMock->shouldReceive('captureException')->once();
        $this->handler->report(new \Exception);
    }

    public function testExceptionInDontReportArray () {
        $this->sentryClientMock->shouldReceive('captureException')->never();
        $this->handler->report(new AuthorizationException);
    }
}
