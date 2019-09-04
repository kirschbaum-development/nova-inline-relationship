<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class EmployeeHasMany extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \KirschbaumDevelopment\NovaInlineRelationship\Tests\Employee::class;

    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            HasMany::make('Bills', 'bills', Bill::class)->inline(),
        ];
    }
}
