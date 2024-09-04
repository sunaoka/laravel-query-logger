# Query logger for Laravel 5.8 to 11

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-query-logger/v/stable)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![License](https://poser.pugx.org/sunaoka/laravel-query-logger/license)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-query-logger)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-%3E=%205.8-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-query-logger/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/laravel-query-logger)

----

## Installation

## Support Policy

| Version (*1) | Laravel (*2) | PHP (*3)  |
|--------------|--------------|-----------|
| [1][v1.x]    | 5.7 - 11     | 7.1 - 8.3 |
| 2            | 10 - 11      | 8.1 - 8.3 |

(*1) Supported Query logger version

(*2) Supported Laravel versions

(*3) Supported PHP versions

```bash
composer require --dev sunaoka/laravel-query-logger
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
