<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gate controllers
    |--------------------------------------------------------------------------
    |
    | Notifications center accept payload from several services and calls
    | matching gate controllers to process messages.
    |
    */

    'controllers' => [
        'DockerHub',
        'GitHub',
        'Jenkins',
        'Phabricator',
    ],

];
