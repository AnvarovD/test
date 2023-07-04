<?php

use Alifuz\Polylog\Handlers\MongoDBHandler;
use Monolog\Formatter\MongoDBFormatter;

return [
    /*
    |--------------------------------------------------------------------------
    | Logging control state
    |--------------------------------------------------------------------------
    |
    | Accepted: true, false
    |
    */
    'http_logging' => env('POLYLOG_HTTP_LOGGING', true),
    'route_logging' => env('POLYLOG_ROUTE_LOGGING', true),
    'app_name' => env('APP_NAME'),
    'write_os_stats' => env('POLYLOG_ON_STATS', false),
    "route_log_driver" => env('POLYLOG_ROUTE_LOG_DRIVER', "logstash_route"),


    /*
    |------------------------------------------------------------------------------------------------
    | Headers data for Authorization don't need to logging, insert your key for Authorization here
    |------------------------------------------------------------------------------------------------
    |
    */
    "forbidden_headers" => [
        "Authorization",
        "token"
    ],

    /*
    |--------------------------------------------------------------------------
    | Define your data scrubbing rules
    |--------------------------------------------------------------------------
    |
    */
    'rules' => [
        'default' => [
            'password',
        ],

        'custom' => []
    ],

    "log_driver" => [
        "mongo" => [
            'driver' => 'monolog',
            'handler' => MongoDBHandler::class,
            'formatter' => MongoDBFormatter::class,
            'with' => [
                'mongodb' => env('REQRESP_MONGO_DB_CONNECTION', 'mongodb://root:123@127.0.0.1:27017/test_logs'),
                'database' => env('REQRESP_MONGO_DB_NAME', 'admin'),
            ]
        ],
        "logstash" => [
            'driver' => 'monolog',
            'handler' => \Alifuz\Polylog\Handlers\LogstashHandler::class,
            'handler_with' => [
                'host' => env('POLYLOG_LOGSTASH_HOST', '127.0.0.1'),
                'user' => env('POLYLOG_LOGSTASH_USER', 'logstash'),
                'password' => env('POLYLOG_LOGSTASH_PASSWORD', 'secret'),
                'port' => env('POLYLOG_LOGSTASH_PORT', 50000),
            ],
            'formatter' => \Alifuz\Polylog\Formatters\LogstashFormatter::class,
            'formatter_with' => [
                'app_name' => config('app.name', 'undefined_name'),
                'app_environment' => config('app.env', 'undefined_environment'),
                'context_key' => env('POLYLOG_CONTEXT_KEY', 'context_json'),
            ]
        ],
        "logstash_route" => [
            'driver' => 'monolog',
            'handler' => \Alifuz\Polylog\Handlers\LogstashHandler::class,
            'handler_with' => [
                'host' => env('POLYLOG_LOGSTASH_HOST', '127.0.0.1'),
                'user' => env('POLYLOG_LOGSTASH_USER', 'logstash'),
                'password' => env('POLYLOG_LOGSTASH_PASSWORD', 'secret'),
                'port' => env('POLYLOG_ROUTE_LOGSTASH_PORT', 50000),
            ],
            'formatter' => \Alifuz\Polylog\Formatters\LogstashFormatter::class,
            'formatter_with' => [
                'app_name' => config('app.name', 'undefined_name'),
                'app_environment' => config('app.env', 'undefined_environment'),
                'context_key' => env('POLYLOG_CONTEXT_KEY', 'context_json'),
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Set true if you want to see lots of data about http errors
    |--------------------------------------------------------------------------
    |
    */
    'debug_http' => env('POLYLOG_DEBUG_HTTP', false)
];
