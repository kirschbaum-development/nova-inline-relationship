<?php

namespace Tests\Unit;

use Tests\Summary;
use Tests\Employee;
use Tests\TestCase;
use Laravel\Nova\Fields\Textarea;
use Tests\Resource\EmployeeMorphOne;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MorphOneTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->employeeResource = new EmployeeMorphOne($this->employeeModel);
        $this->setResourceForModel(Employee::class, EmployeeMorphOne::class);
    }

    public function testResolveEmpty()
    {
        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'summary');

        $this->assertEmpty($inlineField->value);
    }

    public function testResolveWithRelationship()
    {
        $this->employeeModel->summary()->save(Summary::make(['text' => 'summary text']));

        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'summary');

        $this->assertCount(1, $inlineField->value);

        tap($inlineField->value->first(), function ($summary) {
            $this->assertArrayHasKey('text', $summary->all());
            tap($summary->get('text'), function ($text) {
                $this->assertEquals(Textarea::class, $text['component']);
                $this->assertEquals('text', $text['attribute']);
                tap($text['meta'], function ($meta) {
                    $this->assertEquals('textarea-field', $meta['component']);
                    $this->assertEquals('summary text', $meta['value']);
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
            'summary' => [
                [
                    'values' => [
                        'text' => 'summary text',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $newEmployee = new Employee();
        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->summary);

        $newEmployee->save();

        tap($newEmployee->fresh()->summary, function ($summary) {
            $this->assertNotEmpty($summary);
            $this->assertEquals('summary text', $summary->text);
        });
    }

    public function testFillAttributeForUpdate()
    {
        $newEmployee = Employee::create(['name' => 'Test']);
        $newEmployee->summary()->save(Summary::make(['text' => 'summary text']));

        $id = $newEmployee->fresh()->summary->id;

        $updateRequest = [
            'name' => 'Test 2',
            'summary' => [
                [
                    'values' => [
                        'text' => 'new summary text',
                    ],
                    'modelId' => $id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($updateRequest), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->summary, function ($summary) use ($id) {
            $this->assertEquals('new summary text', $summary->text);
            $this->assertEquals($id, $summary->id);
        });
    }

    public function testFillAttributeForDelete()
    {
        $newEmployee = Employee::create(['name' => 'Test']);
        $newEmployee->summary()->save(Summary::make(['text' => 'summary text']));

        $updateRequest = [
            'name' => 'Test 2',
            'summary' => [
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($updateRequest), $newEmployee);

        $this->assertNotEmpty($newEmployee->summary);

        $newEmployee->save();

        $this->assertEmpty($newEmployee->fresh()->summary);
    }
}
