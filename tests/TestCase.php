<?php

namespace Nasqueron\Notifications\Tests;

use Illuminate\Contracts\Console\Kernel;

use Mockery;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    ///
    /// Helpers to mock application services
    ///

    /**
     * Mocks the events dispatcher
     */
    public function disableEvents () {
        // Disables events
        // This allows to test a single component and not all the application
        $mock = Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('fire');
        $mock->shouldReceive('listen');

        $this->app->instance('events', $mock);
    }

    ///
    /// Helpers to post data to gates
    ///

    /**
     * Visits the given URI with a JSON request.
     *
     * @param  string  $uri
     * @param  mixed   $data
     * @param  string  $method
     * @param  array   $headers
     * @return $this
     */
    public function sendJsonPayload ($uri, $data, $method = 'POST', array $headers = []) {
        $content = json_encode($data);
        $headers = array_merge([
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);
        return $this->sendPayload($uri, $content, $method, $headers);
    }

    /**
     * Visits the given URI with a raw request.
     *
     * @param  string  $uri
     * @param  string  $content
     * @param  string  $method
     * @param  array   $headers
     * @return $this
     */
    public function sendPayload ($uri, $content, $method = 'POST', array $headers = []) {
        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
        ], $headers);

        $this->call(
            $method, $uri, [], [], [], $this->transformHeadersToServerVars($headers), $content
        );

        return $this;
    }
}
