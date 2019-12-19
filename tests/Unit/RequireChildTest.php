<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;

class RequireChildTest extends TestCase
{
    use WithFaker;

    /**
     * @var NovaInlineRelationship
     */
    protected $inlineRelationship;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->inlineRelationship = new NovaInlineRelationship('inline-relationship');
    }

    public function testThatRequireChildIsFalseByDefault()
    {
        $this->assertFalse($this->inlineRelationship->requireChild);
    }

    public function testThatRequireChildCanBeSetToTrue()
    {
        $this->inlineRelationship->requireChild();

        $this->assertTrue($this->inlineRelationship->requireChild);
    }
}
