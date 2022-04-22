<?php

namespace Nasqueron\Notifications\Providers;

use Nasqueron\Notifications\Events\DockerHubPayloadEvent;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\JenkinsPayloadEvent;
use Nasqueron\Notifications\Events\NotificationEvent;
use Nasqueron\Notifications\Events\PhabricatorPayloadEvent;
use Nasqueron\Notifications\Listeners\AMQPEventListener;
use Nasqueron\Notifications\Listeners\DockerHubListener;
use Nasqueron\Notifications\Listeners\LastPayloadSaver;
use Nasqueron\Notifications\Listeners\NotificationListener;
use Nasqueron\Notifications\Listeners\PhabricatorListener;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DockerHubPayloadEvent::class => [
            LastPayloadSaver::class,
            NotificationListener::class,
        ],
        GitHubPayloadEvent::class => [
            DockerHubListener::class,
            LastPayloadSaver::class,
            NotificationListener::class,
            PhabricatorListener::class,
        ],
        JenkinsPayloadEvent::class => [
            LastPayloadSaver::class,
            NotificationListener::class,
        ],
        NotificationEvent::class => [
            AMQPEventListener::class,
        ],
        PhabricatorPayloadEvent::class => [
            LastPayloadSaver::class,
            NotificationListener::class,
        ],
    ];

}
