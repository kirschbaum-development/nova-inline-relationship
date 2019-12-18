<?php

namespace Tests\Unit;

use Tests\TestCase;
use Laravel\Nova\Fields\HasOne;
use Tests\Resource\EmployeeTeams;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\UnsupportedRelationshipType;

class NovaInlineRelationshipServiceProviderTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

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
