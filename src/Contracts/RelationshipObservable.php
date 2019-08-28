<?php


namespace KirschbaumDevelopment\NovaInlineRelationship\Contracts;


use Illuminate\Database\Eloquent\Model;

interface RelationshipObservable
{

    public function updating(Model $model, $attribute, $value);

    public function creating(Model $model, $attribute, $value);

    public function created(Model $model, $attribute, $value);
}