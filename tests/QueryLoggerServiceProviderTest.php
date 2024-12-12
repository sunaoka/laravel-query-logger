<?php

declare(strict_types=1);

namespace Sunaoka\LaravelQueryLogger\Tests;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerServiceProviderTest extends TestCase
{
    public function test_query_log_int(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1;/", $log->message);
        });

        DB::select('select ?', [1]);
    }

    public function test_query_log_float(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1\.1;/", $log->message);
        });

        DB::select('select ?', [1.1]);
    }

    public function test_query_log_null(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select null;/", $log->message);
        });

        DB::select('select ?', [null]);
    }

    public function test_query_log_string(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 'string';/", $log->message);
        });

        DB::select('select ?', ['string']);
    }

    public function test_query_log_bool(): void
    {
        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertMatchesRegularExpression("/[[\d.]ms] select 1;/", $log->message);
        });

        DB::select('select ?', [true]);
    }

    public function test_query_log_date_time_interface(): void
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
    public function test_transaction_beginning(): void
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
    public function test_transaction_committed(): void
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
    public function test_transaction_rolled_back(): void
    {
        DB::beginTransaction();

        Log::listen(function (MessageLogged $log) {
            $this->assertSame('debug', $log->level);
            $this->assertSame('ROLLBACK;', $log->message);
        });

        DB::rollBack();
    }
}
