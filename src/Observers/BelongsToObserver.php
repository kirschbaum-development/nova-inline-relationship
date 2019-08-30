<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class BelongsToObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $childModel = $model->{$attribute}()->first();

        if (count($value)) {
            $childModel->update($value[0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function creating(Model $model, $attribute, $value)
    {
        $parentModel = $model->{$attribute}()->getRelated()->newInstance($value[0]);
        $parentModel->save();
        $model->{$attribute}()->associate($parentModel);
    }
}
