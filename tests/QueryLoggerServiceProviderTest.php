<?php

namespace Sunaoka\LaravelQueryLogger\Tests;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;
use Sunaoka\LaravelQueryLogger\QueryLoggerServiceProvider;

class QueryLoggerServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            QueryLoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    public function testQueryLogInt(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select 1;/", $log->message));
        });

        DB::select('select ?', [1]);
    }

    public function testQueryLogFloat(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select 1\.1;/", $log->message));
        });

        DB::select('select ?', [1.1]);
    }

    public function testQueryLogNull(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select NULL;/", $log->message));
        });

        DB::select('select ?', [null]);
    }

    public function testQueryLogString(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select 'string';/", $log->message));
        });

        DB::select('select ?', ['string']);
    }

    public function testQueryLogBool(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select 1;/", $log->message));
        });

        DB::select('select ?', [true]);
    }

    public function testQueryLogDateTimeInterface(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame(1, preg_match("/[[\d.]ms] select '1970-01-01 00:00:00';/", $log->message));
        });

        DB::select('select ?', [Carbon::parse('1970-01-01 00:00:00')]);
    }

    public function testTransactionBeginning(): void
    {
        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('BEGIN;', $log->message);
        });

        DB::beginTransaction();
    }

    public function testTransactionCommitted(): void
    {
        DB::beginTransaction();

        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('COMMIT;', $log->message);
        });

        DB::commit();
    }

    public function testTransactionRolledBack(): void
    {
        DB::beginTransaction();

        Log::listen(function ($log) {
            /** @var MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertSame('ROLLBACK;', $log->message);
        });

        DB::rollBack();
    }
}
