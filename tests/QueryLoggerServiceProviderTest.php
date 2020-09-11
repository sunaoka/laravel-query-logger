<?php

namespace Sunaoka\LaravelQueryLogger\Tests;

use Orchestra\Testbench\TestCase;
use PHPUnit\Runner\Version;
use Sunaoka\LaravelQueryLogger\QueryLoggerServiceProvider;

class QueryLoggerServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            QueryLoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    public function testQueryLog()
    {
        \Log::listen(function ($log) {
            /** @var \Illuminate\Log\Events\MessageLogged $log */
            $this->assertSame('debug', $log->level);
            if (version_compare(Version::id(), '9.1.0', '<')) {
                $this->assertRegExp("/[[0-9.]ms] select '1';/D", $log->message);
            } else {
                $this->assertMatchesRegularExpression("/[[0-9.]ms] select '1';/D", $log->message);
            }
        });

        \DB::select('select ?', [1]);
    }

    public function testTransactionBeginning()
    {
        \Log::listen(function ($log) {
            /** @var \Illuminate\Log\Events\MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('BEGIN;', $log->message);
        });

        \DB::beginTransaction();
    }

    public function testTransactionCommitted()
    {
        \DB::beginTransaction();

        \Log::listen(function ($log) {
            /** @var \Illuminate\Log\Events\MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('COMMIT;', $log->message);
        });

        \DB::commit();
    }

    public function testTransactionRolledBack()
    {
        \DB::beginTransaction();

        \Log::listen(function ($log) {
            /** @var \Illuminate\Log\Events\MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('ROLLBACK;', $log->message);
        });

        \DB::rollBack();
    }
}
