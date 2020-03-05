<?php

namespace Tests\Unit;

use Tests\User;
use Tests\TestCase;
use Tests\Department;
use Laravel\Nova\Fields\Text;
use Tests\Resource\User as UserResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BelongsToTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private $department;

    /**
     * @var UserResource
     */
    private $userResource;

    private $userModel;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->userModel = User::make(['name' => 'test']);
        Department::create(['title' => 'Employee Department'])->users()->save($this->userModel);

        $this->userResource = new UserResource($this->userModel);
        $this->setResourceForModel(User::class, UserResource::class);
    }

    public function testResolveWithRelationship()
    {
        $inlineField = $this->userResource->resolveFieldForAttribute(new NovaRequest(), 'department');
        $this->assertCount(1, $inlineField->value);

        tap($inlineField->value->first(), function ($department) {
            $this->assertArrayHasKey('title', $department->all());
            tap($department->get('title'), function ($title) {
                $this->assertEquals(Text::class, $title['component']);
                $this->assertEquals('title', $title['attribute']);
                tap($title['meta'], function ($meta) {
                    $this->assertEquals('text-field', $meta['component']);
                    $this->assertEquals('Employee Department', $meta['value']);
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
            'department' => [
                [
                    'values' => [
                        'title' => '123123123',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $this->userModel = new User();

        $this->userResource->fill(new NovaRequest($request), $this->userModel);

        $this->assertEmpty($this->userModel->department);

        $this->userModel->save();

        tap($this->userModel->fresh()->department, function ($department) {
            $this->assertNotEmpty($department);
            $this->assertEquals('123123123', $department->title);
        });
    }

    public function testFillAttributeForUpdate()
    {
        $id = $this->userModel->fresh()->department->id;

        $updateRequest = [
            'name' => 'Test 2',
            'department' => [
                [
                    'values' => [
                        'title' => '456456456',
                    ],
                    'modelId' => $id,
                ],
            ],
        ];

        $this->userResource->fillForUpdate(new NovaRequest($updateRequest), $this->userModel);

        $this->userModel->save();

        tap($this->userModel->fresh()->department, function ($department) use ($id) {
            $this->assertEquals('456456456', $department->title);
            $this->assertEquals($id, $department->id);
        });
    }

    public function testFillAttributeWillNotDelete()
    {
        $updateRequest = [
            'name' => 'Test 2',
            'department' => [
            ],
        ];

        $this->userResource->fillForUpdate(new NovaRequest($updateRequest), $this->userModel);

        $this->assertNotEmpty($this->userModel->department);

        $this->userModel->save();

        $this->assertNotEmpty($this->userModel->fresh()->department);
    }
}
