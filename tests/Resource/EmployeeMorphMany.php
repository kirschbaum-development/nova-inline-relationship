<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphOne;

class EmployeeMorphMany extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\Employee::class;

    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            MorphOne::make('Comments', 'comments', Comment::class)->inline(),
        ];
    }
}
