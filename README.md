# Query logger for Laravel 5.8 to 11

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-query-logger/v/stable)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![License](https://poser.pugx.org/sunaoka/laravel-query-logger/license)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-query-logger)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-%3E=%205.8-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-query-logger/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/laravel-query-logger)

----

## Support Policy

| Version (*1) | Laravel (*2) | PHP (*3)  |
|--------------|--------------|-----------|
| [1][v1.x]    | 5.7 - 11     | 7.1 - 8.3 |
| 2            | 10.15 - 12   | 8.1 - 8.4 |

(*1) Supported Query logger version

(*2) Supported Laravel versions

(*3) Supported PHP versions

## Installation

```bash
composer require --dev sunaoka/laravel-query-logger
```

## Configurations

```bash
php artisan vendor:publish --tag=query-logger-config
```

```php
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
```

## Usage

```php
<?php

\DB::beginTransaction();
\App\User::whereEmail('example@example.com')->update(['name' => 'example']);
\DB::commit();

\DB::beginTransaction();
\App\User::whereEmail('example@example.com')->update(['name' => 'example']);
\DB::rollBack();
```

```bash
tail -F storage/logs/laravel.log
```

```bash
[2020-09-11 01:08:37] local.DEBUG: BEGIN;  
[2020-09-11 01:08:37] local.DEBUG: [0.31ms] update "users" set "name" = 'example' where "email" = 'example@example.com';  
[2020-09-11 01:08:37] local.DEBUG: COMMIT;  

[2020-09-11 01:08:37] local.DEBUG: BEGIN;  
[2020-09-11 01:08:37] local.DEBUG: [0.12ms] update "users" set "name" = 'example' where "email" = 'example@example.com';  
[2020-09-11 01:08:37] local.DEBUG: ROLLBACK;  
```

[v1.x]: https://github.com/sunaoka/laravel-query-logger/tree/v1.x
