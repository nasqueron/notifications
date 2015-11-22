<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => Nasqueron\Notifications\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'github' => [
        'analyzer' => [
            'configDir' => storage_path(env('GITHUB_ANALYZER_CONFIG_DIR', 'app/GitHubPayloadAnalyzer'))
        ]
    ],

    'phabricator' => [
        'analyzer' => [
            'configDir' => storage_path(env('PHABRICATOR_ANALYZER_CONFIG_DIR', 'app/PhabricatorPayloadAnalyzer'))
        ]
    ],

    'gate' => [
        'credentials' => storage_path(env('CREDENTIALS', 'app/credentials.json')),
    ]

];
