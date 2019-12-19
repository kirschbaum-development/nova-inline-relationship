<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Profile extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\Profile::class;

    public function fields(Request $request)
    {
        return [
            Text::make('Phone')->rules('required'),
        ];
    }
}
