<?php

namespace Yakuzan\Boiler\Tests;

use Illuminate\Database\Schema\Blueprint;
use Yakuzan\Boiler\BoilerServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [BoilerServiceProvider::class];
    }

    public function tearDown()
    {
        \Schema::drop('lessons');
        \Mockery::close();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        \Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject');
            $table->timestamps();
        });
    }
}
