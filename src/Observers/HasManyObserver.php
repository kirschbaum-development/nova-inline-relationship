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

        for ($i = 0; $i < count($value); $i++) {
            $childModel = $model->{$attribute}()->find($value[$i]['modelId']);

            if (empty($childModel)) {
                $model->{$attribute}()->create($value[$i]['fields']);

                continue;
            }

            $childModel->update($value[$i]['fields']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        $model->{$attribute}()->createMany(Arr::pluck($value, 'fields'));
    }
}
