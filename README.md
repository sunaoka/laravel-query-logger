# Query logger for Laravel 5, 6, 7, 8 and 9

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-query-logger/v/stable)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![License](https://poser.pugx.org/sunaoka/laravel-query-logger/license)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-query-logger)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-5.x%20%7C%206.x%20%7C%207.x%20%7C%208.x%20%7C%209.x-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-query-logger/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-query-logger/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/laravel-query-logger)

----

## Installation

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
