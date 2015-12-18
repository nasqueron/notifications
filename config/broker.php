<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default message broker driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default message broker "driver" that will be
    | used to send messages.
    |
    | Supported: "amqp"
    |
    */

    'driver' => env('BROKER_DRIVER', 'amqp'),

    /*
    |--------------------------------------------------------------------------
    | Message broker connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the message broker connections setup for the application.
    |
    */

    'connections' => [

        'amqp' => [
            'driver'    => 'amqp',
            'host'      => env('BROKER_HOST', 'localhost'),
            'port'      => env('BROKER_PORT', 5672),
            'username'  => env('BROKER_USERNAME', 'guest'),
            'password'  => env('BROKER_PASSWORD', 'guest'),
            'vhost'     => env('BROKER_VHOST', '/'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Message broker targets
    |--------------------------------------------------------------------------
    |
    | For AMQP, there are exchange points. For other brokers, they could be
    | queues if exchanges are not implemented.
    |
    */

    'targets' => [
        // A stream of all payloads sent by GitHub, to provide a gateway
        // GitHub Webhooks API => broker. They are sorted by topics under
        // the pattern <gate door>.<group>.<event type>.
        'github_events' => 'github_events',

        // A stream of selected events, to display a textual notification.
        // They are sorted by topics under the following pattern:
        // <service>.<project>.<group>.<type>
        'notifications' => 'notifications',
    ]

];
