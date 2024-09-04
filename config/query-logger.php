<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Output Log Color
    |--------------------------------------------------------------------------
    |
    | Sets the foreground and background colors of the log output.
    |
    | Supported: "black", "red", "green", "yellow", "blue", "magenta", "cyan",
    |            "white", "default", "gray", "bright-red", "bright-green",
    |            "bright-yellow", "bright-blue", "bright-magenta",
    |            "bright-cyan", "bright-white"
    */

    'color' => [
        'foreground' => env('QUERY_LOGGER_COLOR_FOREGROUND', ''),
        'background' => env('QUERY_LOGGER_COLOR_BACKGROUND', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Slow Query Log
    |--------------------------------------------------------------------------
    |
    | Sets the number of milliseconds to output the slow query.
    | If less than 0 is specified, all logs are output.
    */

    'slow_query' => [
        'milliseconds' => (int) env('QUERY_LOGGER_SLOW_QUERY_MILLISECONDS', 0),
    ],
];
