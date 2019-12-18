<?php

namespace Tests\Resource;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Trix;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tests\Comment::class;

    public function fields(Request $request)
    {
        return [
            Trix::make('text'),
        ];
    }
}
