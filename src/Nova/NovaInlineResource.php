<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;

class NovaInlineResource extends Resource
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            NovaInlineRelationship::make('profile', 'Profile'),
        ];
    }
}
