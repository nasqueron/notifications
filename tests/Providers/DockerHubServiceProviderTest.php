<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Nasqueron\Notifications\Providers\DockerHubServiceProvider;

use Illuminate\Support\Facades\Config;

class DockerHubServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Keruald\DockerHub\Build\TriggerBuildFactory',
            'dockerhub'
        );
    }

    public function testGetTokens () {
        $this->assertSame(
            ['acme/foo' => '0000'],
            DockerHubServiceProvider::getTokens($this->app),
            "The service provider should deserialize DockerHubTokens.json."
        );
    }

    public function testGetTokensWhenFileDoesNotExist () {
        Config::set('services.dockerhub.tokens', 'notexisting.json');

        $this->assertSame(
            [],
            DockerHubServiceProvider::getTokens($this->app),
            "When no tokens file exists, an empty array is used instead."
        );
    }

}
