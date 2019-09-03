<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource;

use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class EmployeeMorphOne extends Resource
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

            MorphOne::make('Summary', 'summary', Summary::class)->inline(),
        ];
    }
}
