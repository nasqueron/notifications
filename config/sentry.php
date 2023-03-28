<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sentry DSN
    |--------------------------------------------------------------------------
    |
    | This option controls the DSN to reach Sentry Relay server.
    |
    */

    'dsn' => env('SENTRY_DSN'),

    /*
    |--------------------------------------------------------------------------
    | Sentry release version
    |--------------------------------------------------------------------------
    |
    | This option controls the release version of the application.
    |
    | Example with dynamic git hash:
    | trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD'))
    |
    */

    'release' => env('SENTRY_RELEASE'),

    /*
    |--------------------------------------------------------------------------
    | Sentry environment
    |--------------------------------------------------------------------------
    |
    | This option controls the release version of the application.
    |
    | When left empty or `null` the Laravel environment will be used.
    |
    */

    'environment' => env('SENTRY_ENVIRONMENT'),

    /*
    |--------------------------------------------------------------------------
    | Sentry breadcrumbs
    |
    | Determines the scope of w hat's capturd in breadcrumbs.
    |--------------------------------------------------------------------------
    |
    */

    'breadcrumbs' => [
        'logs' => true,
        'sql_queries' => true,
        'sql_bindings' => true,
        'queue_info' => true,
        'command_info' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sentry tracing
    |
    | This option controls what's traced or captured as spans.
    |
    | See https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#traces-sample-rate
    | for the sample rate.
    |--------------------------------------------------------------------------
    |
    */

    'tracing' => [
        // Trace queue jobs as their own transactions
        'queue_job_transactions' => env('SENTRY_TRACE_QUEUE_ENABLED', false),

        // Capture queue jobs as spans when executed on the sync driver
        'queue_jobs' => true,

        // Capture SQL queries as spans
        'sql_queries' => true,

        // Try to find out where the SQL query originated from and add it to the query spans
        'sql_origin' => true,

        // Capture views as spans
        'views' => true,

        // Capture HTTP client requests as spans
        'http_client_requests' => true,

        // Indicates if the tracing integrations supplied by Sentry should be loaded
        'default_integrations' => true,

        // Indicates that requests without a matching route should be traced
        'missing_routes' => false,
    ],

    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE') === null ? null : (float)env('SENTRY_TRACES_SAMPLE_RATE'),

    /*
    |--------------------------------------------------------------------------
    | Sentry PII
    |
    | This option controls if Personal Identifiable Information (PII) should
    | be sent to the Relay or scrubbed here.
    |
    | If set at true, PII can still be removed in Relay or Sentry itself.
    |
    | See https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#send-default-pii
    |--------------------------------------------------------------------------
    |
    */

    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

];
