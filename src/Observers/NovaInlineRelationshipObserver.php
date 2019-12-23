<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Laravel\Nova\Nova;
use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Integrations\Integrate;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;
use KirschbaumDevelopment\NovaInlineRelationship\Contracts\RelationshipObservable;
use KirschbaumDevelopment\NovaInlineRelationship\Helpers\NovaInlineRelationshipHelper;

class NovaInlineRelationshipObserver
{
    /**
     * Handle updating event for the model
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function updating(Model $model)
    {
        $this->callEvent($model, 'updating');
    }

    /**
     * Handle updated event for the model
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function created(Model $model)
    {
        $this->callEvent($model, 'created');
    }

    /**
     * Handle updating event for the model
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function creating(Model $model)
    {
        $this->callEvent($model, 'creating');
    }

    /**
     * Handle events for the model
     *
     * @param Model $model
     * @param string $event
     *
     * @return mixed
     */
    public function callEvent(Model $model, string $event)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            $observer = $this->getRelationshipObserver($model, $relationship);

            if ($observer instanceof RelationshipObservable) {
                $observer->{$event}($model, $relationship, $relatedModelAttribs[$relationship] ?? []);
            }
        }
    }

    /**
     * Checks if a relationship is singular
     *
     * @param Model $model
     * @param $relationship
     *
     * @return RelationshipObservable
     */
    public function getRelationshipObserver(Model $model, $relationship): RelationshipObservable
    {
        $className = NovaInlineRelationshipHelper::getObserver($model->{$relationship}());

        return class_exists($className) ? resolve($className) : null;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    protected function getModelRelationships(Model $model)
    {
        return collect(Nova::newResourceFromModel($model)->fields(request()))
            ->flatMap(function ($value) {
                return Integrate::fields($value);
            })
            ->filter(function ($value) {
                return $value->component === 'nova-inline-relationship';
            })
            ->pluck('attribute')
            ->all();
    }
}
