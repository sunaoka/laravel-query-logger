<?php

declare(strict_types=1);

return [
    'color' => [
        'foreground' => env('QUERY_LOGGER_COLOR_FOREGROUND', 'yellow'),
        'background' => env('QUERY_LOGGER_COLOR_BACKGROUND', ''),
    ],
    'slow_query' => [
        'milliseconds' => (int) env('QUERY_LOGGER_SLOW_QUERY_MILLISECONDS', 0),
    ],
];
