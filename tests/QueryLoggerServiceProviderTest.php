<?php

namespace Sunaoka\LaravelQueryLogger\Tests;

use Orchestra\Testbench\TestCase;
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

    public function testRegister()
    {
        \Log::listen(function ($log) {
            /** @var \Illuminate\Log\Events\MessageLogged $log */
            $this->assertSame('debug', $log->level);
            $this->assertRegExp("/[[0-9.]ms] select '1';/D", $log->message);
        });

        \DB::select('select ?', [1]);
    }
}
