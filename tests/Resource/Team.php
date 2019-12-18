<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Team extends Resource
{
    public function fields(Request $request)
    {
        return [
            Text::make('Title'),
        ];
    }
}
