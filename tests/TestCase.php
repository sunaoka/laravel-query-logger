<?php

declare(strict_types=1);

namespace Sunaoka\LaravelQueryLogger\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Sunaoka\LaravelQueryLogger\QueryLoggerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            QueryLoggerServiceProvider::class,
        ];
    }

    /**
     * @param  Application|array{config: Repository}  $app
     */
    protected function defineEnvironment($app): void
    {
        tap($app['config'], static function ($config) {
            /** @var Repository $config */
            $config->set('database.default', 'testbench');
            $config->set('database.connections.testbench', [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ]);
            $config->set('query-logger', [
                'color' => [
                    'foreground' => '',
                    'background' => '',
                ],
                'slow_query' => [
                    'milliseconds' => 0,
                ],
            ]);
        });
    }
}
