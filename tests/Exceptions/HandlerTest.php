<?php

namespace Nasqueron\Notifications\Tests\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Nasqueron\Notifications\Exceptions\Handler;
use Nasqueron\Notifications\Tests\TestCase;

use App;
use Config;
use Mockery;

class HandlerTest extends TestCase {

    /**
     * Illuminate\Foundation\Exceptions\Handler
     */
    private $handler;

    /**
     * Raven_Client
     */
    private $ravenClientMock;

    public function setUp () {
        parent::setUp();

        $this->handler = new Handler(app());

        $this->mockRavenClient();
    }

    protected function mockRavenClient () {
        // Inject into our container a mock of Raven_Client
        $this->ravenClientMock = Mockery::mock('Raven_Client');
        $this->app->instance('raven', $this->ravenClientMock);

        // Environment shouldn't be 'testing' and DSN should be defined,
        // so Handler::report will call Raven to report to Sentry
        Config::set('app.env', 'testing-raven');
        Config::set('services.sentry.dsn', 'mock');
    }

    public function testRavenReport () {
        $this->ravenClientMock->shouldReceive('captureException')->once();
        $this->handler->report(new \Exception);
    }

    public function testExceptionInDontReportArray () {
        $this->ravenClientMock->shouldReceive('captureException')->never();
        $this->handler->report(new AuthorizationException);
    }
}
