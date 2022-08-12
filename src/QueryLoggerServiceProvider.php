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
    public function boot(LoggerInterface $logger)
    {
        $this->app['db']->listen(function(QueryExecuted $query) use ($logger) {
            $bindings = $query->connection->prepareBindings($query->bindings);

            $args = [];
            foreach ($bindings as $binding) {
                if ($binding === null) {
                    $args[] = 'NULL';
                } elseif (is_float($binding) || is_int($binding)) {
                    $args[] = $binding;
                } else {
                    $args[] = $query->connection->getPdo()->quote($binding);
                }
            }

            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $logger->debug(sprintf('[%sms] %s;', $query->time, vsprintf($sql, $args)));
        });

        $this->app['events']->listen(TransactionBeginning::class, function(TransactionBeginning $event) use ($logger) {
            $logger->debug('BEGIN;');
        });

        $this->app['events']->listen(TransactionCommitted::class, function(TransactionCommitted $event) use ($logger) {
            $logger->debug('COMMIT;');
        });

        $this->app['events']->listen(TransactionRolledBack::class, function(TransactionRolledBack $event) use ($logger) {
            $logger->debug('ROLLBACK;');
        });
    }
}
