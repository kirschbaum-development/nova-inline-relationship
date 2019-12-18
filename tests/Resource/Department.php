<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Department extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\Department::class;

    public function fields(Request $request)
    {
        return [
            Text::make('Title'),
        ];
    }
}
