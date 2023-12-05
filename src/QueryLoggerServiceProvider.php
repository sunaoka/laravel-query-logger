<?php

declare(strict_types=1);

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
            if (version_compare($this->app->version(), '10.15.0') >= 0) {
                $sql = $query->connection->getQueryGrammar()
                    ->substituteBindingsIntoRawSql($query->sql, $query->connection->prepareBindings($query->bindings));
            } else {
                $bindings = $query->connection->prepareBindings($query->bindings);

                $args = [];
                foreach ($bindings as $binding) {
                    if ($binding === null) {
                        $args[] = 'null';
                    } elseif (is_float($binding) || is_int($binding)) {
                        $args[] = $binding;
                    } else {
                        $args[] = $query->connection->getPdo()->quote($binding);
                    }
                }

                $sql = str_replace(['%', '?', '%s%s'], ['%%', '%s', '??'], $query->sql);
                $sql = vsprintf($sql, $args);
            }

            $logger->debug(sprintf('[%sms] %s;', $query->time, $sql));
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
