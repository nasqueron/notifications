<?php

namespace Nasqueron\Notifications\Providers;

use Keruald\DockerHub\Build\TriggerBuildFactory;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;


class DockerHubServiceProvider extends ServiceProvider {
    /**
     * Bootstraps the application services.
     */
    public function boot() : void {
    }

    /**
     * Gets the tokens to trigger build for the Docker Hub images.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return array
     */
    public static function getTokens (Application $app) : array {
        $file = $app->make('config')->get('services.dockerhub.tokens');
        $fs = $app->make('filesystem')->disk('local');

        if ($fs->exists($file)) {
            $content = $fs->get($file);
            return json_decode($content, true);
        }

        return [];
    }

    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('dockerhub', function (Application $app) {
            $tokens = DockerHubServiceProvider::getTokens($app);
            return new TriggerBuildFactory(new Client, $tokens);
        });
    }
}
