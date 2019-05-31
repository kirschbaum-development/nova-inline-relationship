<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var Employee */
    protected $testRelationshipMappableModel;

    /**
     * Setup the Tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(dirname(__DIR__) . '/tests/factories'));

        $this->loadMigrationsFrom(realpath(dirname(__DIR__)) . '/migrations');

        $this->setUpDatabase($this->app);

        $this->createTestModels();

        $this->testRelationshipMappableModel = Employee::first();
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });

        $app['db']->connection()->getSchemaBuilder()->create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->softDeletes();
        });
    }

    /**
     * Create RelationshipMappableModel Model for Testing.
     */
    protected function createTestModels(): void
    {
        Employee::create(['name' => 'TestRelationshipMappableModel']);
    }
}
