# Query logger for Laravel 5 and 6.

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-query-logger/v/stable)](https://packagist.org/packages/sunaoka/laravel-query-logger)
[![License](https://poser.pugx.org/sunaoka/laravel-query-logger/license)](https://packagist.org/packages/sunaoka/laravel-query-logger)
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
