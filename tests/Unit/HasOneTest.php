<?php

namespace Tests\Unit;

use Tests\Profile;
use Tests\Employee;
use Tests\TestCase;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\Resource\Employee as EmployeeResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Http\Requests\UpdateResourceRequest;

class HasOneTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->employeeResource = new EmployeeResource($this->employeeModel);
    }

    public function testResolveEmpty()
    {
        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'profile');

        $this->assertEmpty($inlineField->value);
    }

    public function testResolveWithRelationship()
    {
        $this->employeeModel->profile()->save(Profile::make(['phone' => '123234234']));

        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'profile');

        $this->assertInstanceOf(Profile::class, $inlineField->value);
        $this->assertEquals('123234234', $inlineField->value->phone);
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
            'profile' => [
                [
                    'values' => [
                        'phone' => '123123123',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $newEmployee = new Employee();

        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->profile);

        $newEmployee->save();

        tap($newEmployee->fresh()->profile, function ($profile) {
            $this->assertNotEmpty($profile);
            $this->assertEquals('123123123', $profile->phone);
        });
    }

    public function testFillAttributeForUpdate()
    {
        $newEmployee = Employee::create(['name' => 'Test']);
        $newEmployee->profile()->save(Profile::make(['phone' => '123123123']));

        $id = $newEmployee->fresh()->profile->id;

        $updateRequest = [
            'name' => 'Test 2',
            'profile' => [
                [
                    'values' => [
                        'phone' => '456456456',
                    ],
                    'modelId' => $id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($updateRequest), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->profile, function ($profile) use ($id) {
            $this->assertEquals('456456456', $profile->phone);
            $this->assertEquals($id, $profile->id);
        });
    }

    public function testFillAttributeForDelete()
    {
        $newEmployee = Employee::create(['name' => 'Test']);
        $newEmployee->profile()->save(Profile::make(['phone' => '123123123']));

        $updateRequest = [
            'name' => 'Test 2',
            'profile' => [
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($updateRequest), $newEmployee);

        $this->assertNotEmpty($newEmployee->profile);

        $newEmployee->save();

        $this->assertEmpty($newEmployee->fresh()->profile);
    }

    public function testRuleIsEnforced()
    {
        $request = [
            'name' => 'Test',
            'profile' => [
                [
                    'phone' => null,
                ],
            ],
        ];

        $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'profile');

        $this->expectException(ValidationException::class);
        $this->employeeResource::validateForUpdate(new UpdateResourceRequest($request));
    }
}
