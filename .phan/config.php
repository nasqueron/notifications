<?php

use \Phan\Issue;

return [

    // A list of directories that should be parsed for class and
    // method information. After excluding the directories
    // defined in exclude_analysis_directory_list, the remaining
    // files will be statically analyzed for errors.
    //
    // Thus, both first-party and third-party code being used by
    // your application should be included in this list.
    //
    // Laravel folders are hand-picked as to include the root folder throws
    // an exception (see https://github.com/etsy/phan/issues/454).
    'directory_list' => [
        'app',
        'vendor/guzzlehttp/guzzle/src',
        'vendor/keruald/broker/src',
        'vendor/keruald/dockerhub/src',
        'vendor/keruald/github',
        'vendor/keruald/mailgun',
        'vendor/laravel/framework/src/Illuminate/Auth',
        'vendor/laravel/framework/src/Illuminate/Bus',
        'vendor/laravel/framework/src/Illuminate/Console/Scheduling',
        'vendor/laravel/framework/src/Illuminate/Contracts',
        'vendor/laravel/framework/src/Illuminate/Cookie',
        'vendor/laravel/framework/src/Illuminate/Database',
        'vendor/laravel/framework/src/Illuminate/Events',
        'vendor/laravel/framework/src/Illuminate/Foundation',
        'vendor/laravel/framework/src/Illuminate/Http',
        'vendor/laravel/framework/src/Illuminate/Queue',
        'vendor/laravel/framework/src/Illuminate/Routing',
        'vendor/laravel/framework/src/Illuminate/Session',
        'vendor/laravel/framework/src/Illuminate/Support',
        'vendor/laravel/framework/src/Illuminate/View',
        'vendor/mockery/mockery/library',
        'vendor/netresearch/jsonmapper/src',
        'vendor/sentry/sentry/lib/Raven',
    ],

    'file_list' => [
        'vendor/laravel/framework/src/Illuminate/Console/Command.php',
    ],

    // A directory list that defines files that will be excluded
    // from static analysis, but whose class and method
    // information should be included.
    //
    // Generally, you'll want to include the directories for
    // third-party code (such as "vendor/") in this list.
    //
    // n.b.: If you'd like to parse but not analyze 3rd
    //       party code, directories containing that code
    //       should be added to the `directory_list` as
    //       to `exclude_analysis_directory_list`.
    "exclude_analysis_directory_list" => [
        'vendor/',
        'tests/',
    ],

];
