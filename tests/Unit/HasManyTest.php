<?php

namespace Tests\Unit;

use Tests\Bill;
use Tests\Employee;
use Tests\TestCase;
use Laravel\Nova\Fields\Currency;
use Tests\Resource\EmployeeHasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasManyTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->employeeResource = new EmployeeHasMany($this->employeeModel);
        $this->setResourceForModel(Employee::class, EmployeeHasMany::class);
    }

    public function testResolveEmpty()
    {
        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'bills');

        $this->assertEmpty($inlineField->value);
    }

    public function testResolveWithRelationship()
    {
        $this->employeeModel->bills()->save(Bill::make(['amount' => '100']));
        $this->employeeModel->bills()->save(Bill::make(['amount' => '200']));

        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'bills');

        $this->assertCount(2, $inlineField->value);

        $inlineField->value->each(function ($bill) {
            $this->assertArrayHasKey('amount', $bill->all());
            tap($bill->get('amount'), function ($phone) {
                $this->assertEquals(Currency::class, $phone['component']);
                $this->assertEquals('amount', $phone['attribute']);
                $this->assertEquals('number', $phone['options']['type']);
                tap($phone['meta'], function ($meta) {
                    $this->assertEquals('text-field', $meta['component']);
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '100',
                    ],
                ],
            ],
        ];

        $newEmployee = new Employee();
        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(1, $bills);
            $this->assertEquals('100', $bills->first()->amount);
        });
    }

    public function testFillAttributeForCreateMany()
    {
        $request = [
            'values' => [
                'name' => 'New Test',
                'bills' => [
                    [
                        'amount' => '100',
                    ],
                    [
                        'amount' => '200',
                    ],
                ],
            ],
        ];

        $newEmployee = new Employee();
        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(2, $bills);
            $this->assertEquals('100', $bills->first()->amount);
            $this->assertEquals('200', $bills->last()->amount);
        });
    }

    public function testFillAttributeForUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '200',
                    ],
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(1, $bills);
            $this->assertEquals('200', $bills->first()->amount);
        });
    }

    public function testFillAttributeForUpdateMany()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));
        $newEmployee->bills()->save(Bill::make(['amount' => '200']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '300',
                    ],
                    [
                        'amount' => '400',
                    ],
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(2, $bills);
            $this->assertEquals('300', $bills->first()->amount);
            $this->assertEquals('400', $bills->last()->amount);
        });
    }

    public function testFillAttributeForUpdateReverse()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));
        $newEmployee->bills()->save(Bill::make(['amount' => '200']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '200',
                    ],
                    [
                        'amount' => '100',
                    ],
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(2, $bills);
            $this->assertEquals('200', $bills->first()->amount);
            $this->assertEquals('100', $bills->last()->amount);
        });
    }

    public function testFillAttributeForAddByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '300',
                    ],
                    [
                        'amount' => '400',
                    ],
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(1, $newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(2, $bills);
            $this->assertEquals('300', $bills->first()->amount);
            $this->assertEquals('400', $bills->last()->amount);
        });
    }

    public function testFillAttributeForDeleteByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));
        $newEmployee->bills()->save(Bill::make(['amount' => '200']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                    [
                        'amount' => '300',
                    ],
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(2, $newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertCount(1, $bills);
            $this->assertEquals('300', $bills->first()->amount);
        });
    }

    public function testFillAttributeForDeleteOnlyItemByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));

        $request = [
            'values' => [
                'name' => 'Test',
                'bills' => [
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(1, $newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) {
            $this->assertEmpty($bills);
        });
    }
}
