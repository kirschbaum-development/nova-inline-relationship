<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests;

use Laravel\Nova\Nova;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationshipServiceProvider;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource\Employee as EmployeeResource;

abstract class TestCase extends Orchestra
{
    /**
     * @var Employee
     */
    protected $employeeModel;

    /**
     * @var Resource\Employee
     */
    protected $employeeResource;

    /**
     * Setup the Tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->createTestModelsAndResources();
    }

    /**
     * Set up the environment.
     *
     * @param Application $app
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
     * @param Application $app
     */
    protected function setUpDatabase(): void
    {
        $this->cleanupDatabase();
        $this->createTables();
    }

    protected function cleanupDatabase()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('employee_team');
    }

    protected function createTables()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->integer('employee_id');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('employee_team', function (Blueprint $table) {
            $table->increments('employee_id');
            $table->string('team_id');
        });
    }

    /**
     * Create RelationshipMappableModel Model for Testing.
     */
    protected function createTestModelsAndResources(): void
    {
        Nova::$resourcesByModel[Employee::class] = EmployeeResource::class;

        $this->employeeModel = Employee::create(['name' => 'test']);
        $this->employeeResource = new EmployeeResource($this->employeeModel);
    }

    /**
     * Load package service provider.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [NovaInlineRelationshipServiceProvider::class];
    }
}
