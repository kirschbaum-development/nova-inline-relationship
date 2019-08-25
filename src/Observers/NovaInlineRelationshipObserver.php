<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Laravel\Nova\Nova;
use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\NovaInlineRelationship;

class NovaInlineRelationshipObserver
{
    public function updating(Model $model)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            $count = count($relatedModelAttribs[$relationship] ?? []);

            if ($count) {
                if ($this->isSingularRelationship($model, $relationship)) {
                    $count = 1;
                }

                $models = $model->{$relationship}()->get()->all();

                for ($i = 0; $i < $count; $i++) {
                    if ($i < count($models)) {
                        $models[$i]->update($relatedModelAttribs[$relationship][$i]);
                    } else {
                        $model->{$relationship}()->create($relatedModelAttribs[$relationship][$i]);
                    }
                }

                if ($count < count($models)) {
                    for ($i = $count; $i < count($models); $i++) {
                        $models[$i]->delete();
                    }
                }
            }
        }
    }

    public function created(Model $model)
    {
        $modelClass = get_class($model);

        $relationships = $this->getModelRelationships($model);

        $relatedModelAttribs = NovaInlineRelationship::$observedModels[$modelClass];

        foreach ($relationships as $relationship) {
            if ($this->isSingularRelationship($model, $relationship)) {
                $model->{$relationship}()->create($relatedModelAttribs[$relationship][0]);
            } else {
                $model->{$relationship}()->createMany($relatedModelAttribs[$relationship]);
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
    public function isSingularRelationship(Model $model, $key): bool
    {
        return ! (Str::contains(class_basename($model->{$key}()), 'Many'));
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
