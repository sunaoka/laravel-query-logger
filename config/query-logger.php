<?php

declare(strict_types=1);

return [
    'color' => [
        'foreground' => env('QUERY_LOGGER_COLOR_FG', 'yellow'),
        'background' => env('QUERY_LOGGER_COLOR_BG', ''),
    ],
];
