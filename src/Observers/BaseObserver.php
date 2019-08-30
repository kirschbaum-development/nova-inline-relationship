<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Contracts\RelationshipObservable;

abstract class BaseObserver implements RelationshipObservable
{
    public function updating(Model $model, $attribute, $value)
    {
    }

    public function creating(Model $model, $attribute, $value)
    {
    }

    public function created(Model $model, $attribute, $value)
    {
    }
}
