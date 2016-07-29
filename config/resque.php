<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Connection
    |--------------------------------------------------------------------------
    |
    */

    'connection' => [
        'server' => env('RESQUE_REDIS_SERVER', 'localhost:6379'),
        'db'   => env('RESQUE_REDIS_DB', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Track Status
    |--------------------------------------------------------------------------
    |
    */

    'queuePrefix' => env('RESQUE_QUEUE_PREFIX', null),
    'trackStatus' => env('RESQUE_TRACK_STATUS', false),

];
