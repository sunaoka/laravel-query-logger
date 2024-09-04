<?php

declare(strict_types=1);

namespace Sunaoka\LaravelQueryLogger;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Color;

class QueryLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/query-logger.php',
            'query-logger'
        );
    }

    public function boot(LoggerInterface $logger, Repository $config, Dispatcher $events): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__).'/config/query-logger.php' => $this->app->configPath('query-logger.php'),
            ], 'query-logger-config');
        }

        $color = new Color(
            $config->get('query-logger.color.foreground', ''),  // @phpstan-ignore argument.type
            $config->get('query-logger.color.background', ''),  // @phpstan-ignore argument.type
        );

        $events->listen(QueryExecuted::class, function (QueryExecuted $event) use ($logger, $color) {
            $sql = $event->connection
                ->getQueryGrammar()
                ->substituteBindingsIntoRawSql(
                    sql: $event->sql,
                    bindings: $event->connection->prepareBindings($event->bindings),
                );

            $logger->debug("[{$event->time}ms] ".$color->apply("{$sql};"));
        });

        $events->listen(TransactionBeginning::class, function (TransactionBeginning $event) use ($logger, $color) {
            $logger->debug($color->apply('BEGIN;'));
        });

        $events->listen(TransactionCommitted::class, function (TransactionCommitted $event) use ($logger, $color) {
            $logger->debug($color->apply('COMMIT;'));
        });

        $events->listen(TransactionRolledBack::class, function (TransactionRolledBack $event) use ($logger, $color) {
            $logger->debug($color->apply('ROLLBACK;'));
        });
    }
}
