<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class NovaInlineRelationshipObserver
{
    public function updating(Model $model)
    {
        $relationships = array_keys($model::getPropertyMap());

        $relatedModelAttribs = $model->getAttributes();

        foreach ($relationships as $relationship) {
            $count = count($relatedModelAttribs[$relationship] ?? []);
            //ToDo: Deduce how to fix
            if ($count) {
                if ($model->isSingularRelationship($relationship)) {
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
        $relationships = array_keys($model::getPropertyMap());

        $relatedModelAttribs = $model->getAttributes();

        foreach ($relationships as $relationship) {
            if ($model->isSingularRelationship($relationship)) {
                $model->{$relationship}()->create($relatedModelAttribs[$relationship][0]);
            } else {
                $model->{$relationship}()->createMany($relatedModelAttribs[$relationship]);
            }
        }
    }
}
