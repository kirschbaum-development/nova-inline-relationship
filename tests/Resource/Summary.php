<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Textarea;

class Summary extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\Summary::class;

    public function fields(Request $request)
    {
        return [
            Textarea::make('text'),
        ];
    }
}
