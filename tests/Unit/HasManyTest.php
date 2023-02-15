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
            tap($bill->get('amount'), function ($amount) {
                $this->assertEquals(Currency::class, $amount['component']);
                $this->assertEquals('amount', $amount['attribute']);
                $this->assertEquals('number', $amount['meta']['type']);
                tap($amount['meta'], function ($meta) {
                    $this->assertEquals('currency-field', $meta['component']);
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '100',
                    ],
                    'modelId' => 0,
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
            'name' => 'New Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '100',
                    ],
                    'modelId' => 0,
                ],
                [
                    'values' => [
                        'amount' => '200',
                    ],
                    'modelId' => 0,
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

        $id = $newEmployee->fresh()->bills->first()->id;

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '200',
                    ],
                    'modelId' => $id,
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

        $bill1 = $newEmployee->fresh()->bills->first();
        $bill2 = $newEmployee->fresh()->bills->reverse()->first();

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '300',
                    ],
                    'modelId' => $bill1->id,
                ],
                [
                    'values' => [
                        'amount' => '400',
                    ],
                    'modelId' => $bill2->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) use ($bill1, $bill2) {
            $this->assertCount(2, $bills);
            $this->assertEquals('300', $bill1->fresh()->amount);
            $this->assertEquals('400', $bill2->fresh()->amount);
        });
    }

    public function testFillAttributeForUpdateReverse()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));
        $newEmployee->bills()->save(Bill::make(['amount' => '200']));

        $bill1 = $newEmployee->fresh()->bills->first();
        $bill2 = $newEmployee->fresh()->bills->reverse()->first();

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '200',
                    ],
                    'modelId' => $bill1->id,
                ],
                [
                    'values' => [
                        'amount' => '100',
                    ],
                    'modelId' => $bill2->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) use ($bill1, $bill2) {
            $this->assertCount(2, $bills);
            tap($bill1->fresh(), function ($bill) {
                $this->assertEquals('200', $bill->amount);
            });
            tap($bill2->fresh(), function ($bill) {
                $this->assertEquals('100', $bill->amount);
            });
        });
    }

    public function testFillAttributeForUpdateByOrder()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));
        $newEmployee->bills()->save(Bill::make(['amount' => '200']));

        $bill1 = $newEmployee->fresh()->bills->first();
        $bill2 = $newEmployee->fresh()->bills->reverse()->first();

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '200',
                    ],
                    'modelId' => $bill2->id,
                ],
                [
                    'values' => [
                        'amount' => '100',
                    ],
                    'modelId' => $bill1->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) use ($bill1, $bill2) {
            $this->assertCount(2, $bills);
            tap($bill1->fresh(), function ($bill) {
                $this->assertEquals('100', $bill->amount);
                $this->assertEquals(1, $bill->weight);
            });
            tap($bill2->fresh(), function ($bill) {
                $this->assertEquals('200', $bill->amount);
                $this->assertEquals(0, $bill->weight);
            });
        });
    }

    public function testFillAttributeForAddByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));

        $bill = $newEmployee->fresh()->bills->first();

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '300',
                    ],
                    'modelId' => $bill->id,
                ],
                [
                    'values' => [
                        'amount' => '400',
                    ],
                    'modelId' => 0,
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

        $bill = $newEmployee->fresh()->bills->first();

        $request = [
            'name' => 'Test',
            'bills' => [
                [
                    'values' => [
                        'amount' => '300',
                    ],
                    'modelId' => $bill->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(2, $newEmployee->bills);

        $newEmployee->save();

        tap($newEmployee->fresh()->bills, function ($bills) use ($bill) {
            $this->assertCount(1, $bills);
            $this->assertEquals('300', $bill->fresh()->amount);
        });
    }

    public function testFillAttributeForDeleteOnlyItemByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->bills()->save(Bill::make(['amount' => '100']));

        $request = [
            'name' => 'Test',
            'bills' => [
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
