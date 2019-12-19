<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\User::class;

    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            BelongsTo::make('Department', 'department', Department::class)->inline(),
        ];
    }
}
