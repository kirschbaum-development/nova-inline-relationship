<?php

namespace Tests\Unit;

use Tests\Comment;
use Tests\Employee;
use Tests\TestCase;
use Laravel\Nova\Fields\Trix;
use Tests\Resource\EmployeeMorphMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MorphManyTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @before
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->employeeResource = new EmployeeMorphMany($this->employeeModel);
        $this->setResourceForModel(Employee::class, EmployeeMorphMany::class);
    }

    public function testResolveEmpty()
    {
        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'comments');

        $this->assertEmpty($inlineField->value);
    }

    public function testResolveWithRelationship()
    {
        $this->employeeModel->comments()->save(Comment::make(['text' => 'comment 1']));
        $this->employeeModel->comments()->save(Comment::make(['text' => 'comment 2']));

        $inlineField = $this->employeeResource->resolveFieldForAttribute(new NovaRequest(), 'comments');

        $this->assertCount(2, $inlineField->value);

        $inlineField->value->each(function ($comment) {
            $this->assertArrayHasKey('text', $comment->all());
            tap($comment->get('text'), function ($phone) {
                $this->assertEquals(Trix::class, $phone['component']);
                $this->assertEquals('text', $phone['attribute']);
                tap($phone['meta'], function ($meta) {
                    $this->assertEquals('trix-field', $meta['component']);
                });
            });
        });
    }

    public function testFillAttributeForCreate()
    {
        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 1',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $newEmployee = new Employee();
        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->comments);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) {
            $this->assertCount(1, $comments);
            $this->assertEquals('comment 1', $comments->first()->text);
        });
    }

    public function testFillAttributeForCreateMany()
    {
        $request = [
            'name' => 'New Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 1',
                    ],
                    'modelId' => 0,
                ],
                [
                    'values' => [
                        'text' => 'comment 2',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $newEmployee = new Employee();
        $this->employeeResource->fill(new NovaRequest($request), $newEmployee);

        $this->assertEmpty($newEmployee->comments);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) {
            $this->assertCount(2, $comments);
            $this->assertEquals('comment 1', $comments->first()->text);
            $this->assertEquals('comment 2', $comments->last()->text);
        });
    }

    public function testFillAttributeForUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));

        $id = $newEmployee->fresh()->comments->first()->id;

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 2',
                    ],
                    'modelId' => $id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) {
            $this->assertCount(1, $comments);
            $this->assertEquals('comment 2', $comments->first()->text);
        });
    }

    public function testFillAttributeForUpdateMany()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 2']));

        $comment1 = $newEmployee->fresh()->comments->first();
        $comment2 = $newEmployee->fresh()->comments->reverse()->first();

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 3',
                    ],
                    'modelId' => $comment1->id,
                ],
                [
                    'values' => [
                        'text' => 'comment 4',
                    ],
                    'modelId' => $comment2->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) use ($comment1, $comment2) {
            $this->assertCount(2, $comments);
            $this->assertEquals('comment 3', $comment1->fresh()->text);
            $this->assertEquals('comment 4', $comment2->fresh()->text);
        });
    }

    public function testFillAttributeForUpdateReverse()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 2']));

        $comment1 = $newEmployee->fresh()->comments->first();
        $comment2 = $newEmployee->fresh()->comments->reverse()->first();

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 2',
                    ],
                    'modelId' => $comment1->id,
                ],
                [
                    'values' => [
                        'text' => 'comment 1',
                    ],
                    'modelId' => $comment2->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) use ($comment1, $comment2) {
            $this->assertCount(2, $comments);
            $this->assertEquals('comment 2', $comment1->fresh()->text);
            $this->assertEquals('comment 1', $comment2->fresh()->text);
        });
    }

    public function testFillAttributeForUpdateByOrder()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 2']));

        $comment1 = $newEmployee->fresh()->comments->first();
        $comment2 = $newEmployee->fresh()->comments->reverse()->first();

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 2',
                    ],
                    'modelId' => $comment2->id,
                ],
                [
                    'values' => [
                        'text' => 'comment 1',
                    ],
                    'modelId' => $comment1->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) use ($comment1, $comment2) {
            $this->assertCount(2, $comments);
            tap($comment1->fresh(), function ($comment) {
                $this->assertEquals('comment 1', $comment->text);
                $this->assertEquals(1, $comment->weight);
            });
            tap($comment2->fresh(), function ($comment) {
                $this->assertEquals('comment 2', $comment->text);
                $this->assertEquals(0, $comment->weight);
            });
        });
    }

    public function testFillAttributeForAddByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));

        $comment = $newEmployee->fresh()->comments->first();

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 3',
                    ],
                    'modelId' => $comment->id,
                ],
                [
                    'values' => [
                        'text' => 'comment 4',
                    ],
                    'modelId' => 0,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(1, $newEmployee->comments);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) {
            $this->assertCount(2, $comments);
            $this->assertEquals('comment 3', $comments->first()->text);
            $this->assertEquals('comment 4', $comments->last()->text);
        });
    }

    public function testFillAttributeForDeleteByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 2']));

        $comment = $newEmployee->fresh()->comments->first();

        $request = [
            'name' => 'Test',
            'comments' => [
                [
                    'values' => [
                        'text' => 'comment 3',
                    ],
                    'modelId' => $comment->id,
                ],
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(2, $newEmployee->comments);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) use ($comment) {
            $this->assertCount(1, $comments);
            $this->assertEquals('comment 3', $comment->fresh()->text);
        });
    }

    public function testFillAttributeForDeleteOnlyItemByUpdate()
    {
        $newEmployee = Employee::create(['name' => 'test']);
        $newEmployee->comments()->save(Comment::make(['text' => 'comment 1']));

        $request = [
            'name' => 'Test',
            'comments' => [
            ],
        ];

        $this->employeeResource->fillForUpdate(new NovaRequest($request), $newEmployee);

        $this->assertCount(1, $newEmployee->comments);

        $newEmployee->save();

        tap($newEmployee->fresh()->comments, function ($comments) {
            $this->assertEmpty($comments);
        });
    }
}
