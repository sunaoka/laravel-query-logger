<?php

namespace Sunaoka\LaravelQueryLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
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
        $this->app['db']->listen(function(QueryExecuted $query) {
            $bindings = $query->connection->prepareBindings($query->bindings);
            $args = array_map([$query->connection->getPdo(), 'quote'], $bindings);
            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $logger = app(LoggerInterface::class);
            $logger->debug(sprintf('[%sms] %s;', $query->time, vsprintf($sql, $args)));
        });

        $this->app['events']->listen(TransactionBeginning::class, function(TransactionBeginning $event) {
            $logger = app(LoggerInterface::class);
            $logger->debug('BEGIN;');
        });

        $this->app['events']->listen(TransactionCommitted::class, function(TransactionCommitted $event) {
            $logger = app(LoggerInterface::class);
            $logger->debug('COMMIT;');
        });

        $this->app['events']->listen(TransactionRolledBack::class, function(TransactionRolledBack $event) {
            $logger = app(LoggerInterface::class);
            $logger->debug('ROLLBACK;');
        });
    }
}
