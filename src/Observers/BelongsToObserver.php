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
        $parentModel = $model->{$attribute}()->first();

        if (empty($parentModel)) {
            return $this->creating($model, $attribute, $value);
        }

        if (count($value)) {
            $parentModel->update($value[0]['fields']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function creating(Model $model, $attribute, $value)
    {
        if (count($value)) {
            $parentModel = $model->{$attribute}()->getRelated()->newInstance($value[0]['fields']);
            $parentModel->save();
            $model->{$attribute}()->associate($parentModel);
        }
    }
}
