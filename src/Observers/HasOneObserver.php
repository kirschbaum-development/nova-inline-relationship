<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class HasOneObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $childModel = $model->{$attribute}()->first();

        if (! empty($childModel)) {
            count($value)
                ? $childModel->update($value[0]['values'])
                : $childModel->delete();
        } elseif (count($value)) {
            $model->{$attribute}()->create($value[0]['values']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        if (count($value)) {
            $model->{$attribute}()->create($value[0]['values']);
        }
    }
}
