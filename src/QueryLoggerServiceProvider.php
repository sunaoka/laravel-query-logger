<?php

namespace Sunaoka\LaravelQueryLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class QueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('db')->listen(function(QueryExecuted $query) {
            $bindings = $query->connection->prepareBindings($query->bindings);
            $args = array_map([$query->connection->getPdo(), 'quote'], $bindings);
            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $logger = app(LoggerInterface::class);
            $logger->debug(sprintf('[%sms] %s;', $query->time, vsprintf($sql, $args)));
        });
    }
}
