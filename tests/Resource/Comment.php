<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests\Resource;

use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \KirschbaumDevelopment\NovaInlineRelationship\Tests\Comment::class;

    public function fields(Request $request)
    {
        return [
            Trix::make('text'),
        ];
    }
}
