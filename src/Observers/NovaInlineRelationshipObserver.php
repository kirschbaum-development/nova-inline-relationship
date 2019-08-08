<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class NovaInlineRelationshipObserver
{
    public function updating(Model $model)
    {
        dd($model);

        $relationships = array_keys(static::getPropertyMap());

        foreach ($relationships as $relationship) {
            $count = count($model->relatedModelAttribs[$relationship]);

            if ($model->isSingularRelationship($relationship)) {
                $count = 1;
            }

            $models = $model->{$relationship}()->get()->all();

            for ($i = 0; $i < $count; $i++) {
                if ($i < count($models)) {
                    $models[$i]->update($model->relatedModelAttribs[$relationship][$i]);
                } else {
                    $model->{$relationship}()->create($model->relatedModelAttribs[$relationship][$i]);
                }
            }

            if ($count < count($models)) {
                for ($i = $count; $i < count($models); $i++) {
                    $models[$i]->delete();
                }
            }
        }
    }

    public function created(Model $model)
    {
        $relationships = array_keys(static::getPropertyMap());

        foreach ($relationships as $relationship) {
            if ($model->isSingularRelationship($relationship)) {
                $model->{$relationship}()->create($model->relatedModelAttribs[$relationship][0]);
            } else {
                $model->{$relationship}()->createMany($model->relatedModelAttribs[$relationship]);
            }
        }
    }
}
