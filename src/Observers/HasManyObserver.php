<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Helpers\FieldHelper;

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

            $field = FieldHelper::generate($value[$i]['fields']);

            if (empty($childModel)) {
                $model->{$attribute}()->create($field);

                continue;
            }

            $childModel->update($field);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        $fields = FieldHelper::generateMany(Arr::pluck($value, 'fields'));
        $model->{$attribute}()->createMany($fields);
    }
}
