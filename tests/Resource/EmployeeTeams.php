<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsToMany;

class EmployeeTeams extends Resource
{
    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            BelongsToMany::make('Teams', 'teams', Team::class)->inline(),
        ];
    }
}
