<?php

namespace Nasqueron\Notifications\Tests\Exceptions;

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

    public function setUp () {
        parent::setUp();

        $logger = App::make('log');
        $this->handler = new Handler($logger);
    }

    protected function mockRavenClient () {
        $mock = Mockery::mock('Raven_Client');
        $mock->shouldReceive('captureException')->once();

        Config::set('app.env', 'testing-raven');
        Config::set('services.sentry.dsn', 'mock');
        $this->app->instance('raven', $mock);
    }

    public function testRavenReport () {
        $this->mockRavenClient();
        $this->handler->report(new \Exception);
    }
}
