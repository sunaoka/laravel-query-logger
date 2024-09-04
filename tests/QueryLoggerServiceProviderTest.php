<?php

declare(strict_types=1);

namespace Sunaoka\LaravelQueryLogger\Tests;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerServiceProviderTest extends TestCase
{
    public function testQueryLogInt(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1;/", $log->message);
        });

        DB::select('select ?', [1]);
    }

    public function testQueryLogFloat(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1\.1;/", $log->message);
        });

        DB::select('select ?', [1.1]);
    }

    public function testQueryLogNull(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select null;/", $log->message);
        });

        DB::select('select ?', [null]);
    }

    public function testQueryLogString(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 'string';/", $log->message);
        });

        DB::select('select ?', ['string']);
    }

    public function testQueryLogBool(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1;/", $log->message);
        });

        DB::select('select ?', [true]);
    }

    public function testQueryLogDateTimeInterface(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select '1970-01-01 00:00:00';/", $log->message);
        });

        DB::select('select ?', [Carbon::parse('1970-01-01 00:00:00')]);
    }

    /**
     * @throws \Throwable
     */
    public function testTransactionBeginning(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertSame('BEGIN;', $log->message);
        });

        DB::beginTransaction();
    }

    /**
     * @throws \Throwable
     */
    public function testTransactionCommitted(): void
    {
        DB::beginTransaction();

        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertSame('COMMIT;', $log->message);
        });

        DB::commit();
    }

    /**
     * @throws \Throwable
     */
    public function testTransactionRolledBack(): void
    {
        DB::beginTransaction();

        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertSame('ROLLBACK;', $log->message);
        });

        DB::rollBack();
    }
}
