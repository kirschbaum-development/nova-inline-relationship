<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class HasOneObserver
{
    public function updating(Model $model, $attribute, $value)
    {
        $count = count($value);

        $childModels = $model->{$attribute}()->get()->all();

        for ($i = 0; $i < $count; $i++) {
            if ($i < count($childModels)) {
                $childModels[$i]->update($value[$i]);
            } else {
                $model->{$attribute}()->create($value[$i]);
            }
        }

        if ($count < count($childModels)) {
            for ($i = $count; $i < count($childModels); $i++) {
                $childModels[$i]->delete();
            }
        }
    }

    public function created(Model $model, $attribute, $value)
    {
        $model->{$attribute}()->createMany($value);
    }
}
