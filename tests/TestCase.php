<?php

namespace Tests;

use App\Nova\Resource;
use Laravel\Nova\Nova;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orchestra\Testbench\TestCase as Orchestra;
use Tests\Resource\Employee as EmployeeResource;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationshipServiceProvider;

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

        $this->createNovaRequestMock();
    }

    /**
     * Set resource for a model in Nova
     *
     * @param string $model
     * @param string $resource
     */
    public function setResourceForModel(string $model, string $resource)
    {
        Nova::$resourcesByModel[$model] = $resource;
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

    /**
     * Remove existing tables
     */
    protected function cleanupDatabase()
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('users');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('summaries');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('employee_team');
    }

    /**
     * Generate tables required for tests
     */
    protected function createTables()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id');
            $table->string('name');
            $table->timestamps();
        });

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

        $this->app['db']->connection()->getSchemaBuilder()->create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 5, 2);
            $table->integer('employee_id');
            $table->integer('weight')->default(0);
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->morphs('summarizable');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('weight')->default(0);
            $table->morphs('commentable');
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
            $table->timestamps();
        });
    }

    /**
     * Create RelationshipMappableModel Model for Testing.
     */
    protected function createTestModelsAndResources(): void
    {
        $this->setResourceForModel(Employee::class, EmployeeResource::class);

        $this->employeeModel = Employee::create(['name' => 'test']);
        $this->employeeResource = new EmployeeResource($this->employeeModel);
    }

    protected function createNovaRequestMock()
    {
        $request = NovaRequest::create('', 'GET', [
            'editing' => true,
            'editMode' => 'create',
        ]);

        $this->app->instance(NovaRequest::class, $request);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [NovaInlineRelationshipServiceProvider::class];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        NovaInlineRelationship::$observedModels = [];
    }
}
