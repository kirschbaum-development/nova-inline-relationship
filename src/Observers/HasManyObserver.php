<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class HasManyObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $count = count($value);

        $childModels = $model->{$attribute}()->get()->all();

        for ($i = 0; $i < $count; $i++) {
            $i < count($childModels)
                ? $childModels[$i]->update($value[$i])
                : $model->{$attribute}()->create($value[$i]);
        }

        if ($count < count($childModels)) {
            for ($i = $count; $i < count($childModels); $i++) {
                $childModels[$i]->delete();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        $model->{$attribute}()->createMany($value);
    }
}
