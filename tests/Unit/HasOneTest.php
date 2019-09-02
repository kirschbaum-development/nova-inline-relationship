<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Unit;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\Profile;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\Employee;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\TestCase;

class HasOneTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();
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

        $this->assertCount(1, $inlineField->value);

        tap($inlineField->value->first(), function ($profile) {
            $this->assertArrayHasKey('phone', $profile->all());
            tap($profile->get('phone'), function ($phone) {
                $this->assertEquals($phone['component'], Text::class);
                $this->assertEquals($phone['attribute'], 'phone');
                tap($phone['meta'], function ($meta) {
                    $this->assertEquals($meta['component'], 'text-field');
                    $this->assertEquals($meta['value'], '123234234');
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
        ];

        $newEmployee = new Employee();
        //$this->employeeResource->fill(new NovaRequest($request), $newEmployee));
    }
}
