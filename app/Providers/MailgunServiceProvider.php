<?php

namespace Nasqueron\Notifications\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client;
use Keruald\Mailgun\MailgunMessageFactory;

class MailgunServiceProvider extends ServiceProvider {
    /**
     * Bootstraps the application services.
     *
     * @return void
     */
    public function boot() {
    }

    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('mailgun', function (Application $app) {
            $config = $app->make('config');
            $key = $config->get('services.mailgun.secret');

            return new MailgunMessageFactory(new Client, $key);
        });
    }
}
