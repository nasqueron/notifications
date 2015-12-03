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

];