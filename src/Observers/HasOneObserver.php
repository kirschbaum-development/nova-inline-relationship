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
            if (count($value)) {
                $childModel->update($value[0]);
            } else {
                $childModel->delete();
            }
        } elseif (count($value)) {
            $model->{$attribute}()->create($value[0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        if (count($value)) {
            $model->{$attribute}()->create($value[0]);
        }
    }
}
