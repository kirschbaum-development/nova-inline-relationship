<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Profile extends Resource
{
    public function fields(Request $request)
    {
        return [
            Text::make('Phone'),
        ];
    }
}
