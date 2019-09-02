<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class Employee extends Resource
{
    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            HasMany::make('Profile', 'profile', Profile::class)->inline(),
        ];
    }
}
