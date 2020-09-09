# Query logger for Laravel 5, 6, 7 and 8

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-query-logger/v/stable)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![License](https://poser.pugx.org/sunaoka/laravel-query-logger/license)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-query-logger)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-5.x%20%7C%206.x%20%7C%207.x%20%7C%208.x-red)](https://laravel.com/)
[![Build Status](https://travis-ci.org/sunaoka/laravel-query-logger.svg?branch=develop)](https://travis-ci.org/sunaoka/laravel-query-logger)
[![codecov](https://codecov.io/gh/sunaoka/laravel-query-logger/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/laravel-query-logger)

----

## Installation

```bash
composer require --dev sunaoka/laravel-query-logger
```

## Usage

```php
<?php

\App\User::whereEmail('example@example.com')->get();
```

```bash
tail -F storage/logs/laravel.log
```

```bash
[2019-02-13 01:57:40] local.DEBUG: [50.12ms] select * from "users" where "email" = 'example@example.com';  
```
