<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class HasManyObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $model->{$attribute}()
            ->whereNotIn('id', Arr::pluck($value, 'modelId'))
            ->get()
            ->each
            ->delete();

        $childModels = $model->{$attribute}()->get()->all();

        for ($i = 0; $i < $count; $i++) {
            $i < count($childModels)
                ? $childModels[$i]->update($value[$i])
                : $model->{$attribute}()->create($value[$i]);
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
