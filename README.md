# Query logger for Laravel.

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
