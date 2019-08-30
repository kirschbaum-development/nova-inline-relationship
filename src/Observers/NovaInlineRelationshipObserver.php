<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Laravel\Nova\Nova;
use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;
use KirschbaumDevelopment\NovaInlineRelationship\Contracts\RelationshipObservable;

class NovaInlineRelationshipObserver
{
    public function updating(Model $model)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            $observer = $this->getRelationshipObserver($model, $relationship);

            if ($observer instanceof RelationshipObservable) {
                $observer->updating($model, $relationship, $relatedModelAttribs[$relationship] ?? []);
            }
        }
    }

    public function created(Model $model)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            $observer = $this->getRelationshipObserver($model, $relationship);

            if ($observer instanceof RelationshipObservable) {
                $observer->created($model, $relationship, $relatedModelAttribs[$relationship] ?? []);
            }
        }
    }

    public function creating(Model $model)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            $observer = $this->getRelationshipObserver($model, $relationship);

            if ($observer instanceof RelationshipObservable) {
                $observer->creating($model, $relationship, $relatedModelAttribs[$relationship] ?? []);
            }
        }
    }

    /**
     * Checks if a relationship is singular
     *
     * @param Model $model
     * @param $key
     *
     * @return bool
     */
    public function getRelationshipName(Model $model, $key): bool
    {
        return class_basename($model->{$key}());
    }

    /**
     * Checks if a relationship is singular
     *
     * @param Model $model
     * @param $key
     *
     * @return RelationshipObservable
     */
    public function getRelationshipObserver(Model $model, $key): RelationshipObservable
    {
        $className = '\\KirschbaumDevelopment\\NovaInlineRelationship\\Observers\\' . class_basename($model->{$key}()) . 'Observer';

        return class_exists($className) ? app($className) : null;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    protected function getModelRelationships(Model $model)
    {
        $relationships = collect(Nova::newResourceFromModel($model)->fields(request()))->filter(function ($value) {
            return $value->component === 'nova-inline-relationship';
        })->pluck('attribute')->all();

        return $relationships;
    }
}
