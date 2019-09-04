<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Unit;

use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\TestCase;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;
use KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource\EmployeeTeams;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\UnsupportedRelationshipType;

class NovaInlineRelationshipServiceProviderTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testItResolvesInlineRelationshipToNovaInlineRelationshipField()
    {
        $field = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'profile');

        $this->assertInstanceOf(NovaInlineRelationship::class, $field);
        $this->assertNotInstanceOf(HasOne::class, $field);
    }

    public function testItThrowsErrorForUnsupportedRelationships()
    {
        $employeeWithTeamsResource = new EmployeeTeams($this->employeeModel);

        $this->expectException(UnsupportedRelationshipType::class);
        $employeeWithTeamsResource->resolveFieldForAttribute(new NovaRequest(), 'teams');
    }
}
