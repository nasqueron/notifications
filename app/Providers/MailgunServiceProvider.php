<?php

namespace Nasqueron\Notifications\Providers;

use Keruald\Mailgun\MailgunMessageFactory;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MailgunServiceProvider extends ServiceProvider {
    /**
     * Bootstraps the application services.
     */
    public function boot() : void {
    }

    /**
     * Registers the application services.
     */
    public function register() : void {
        $this->app->singleton('mailgun', function (Application $app) {
            $config = $app->make('config');
            $key = $config->get('services.mailgun.secret');

            return new MailgunMessageFactory(new Client, $key);
        });
    }
}
