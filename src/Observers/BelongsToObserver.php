<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Helpers\FieldHelper;

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
            $parentModel->update(FieldHelper::generate($value[0]['fields']));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function creating(Model $model, $attribute, $value)
    {
        if (count($value)) {
            $field = FieldHelper::generate($value[0]['fields']);
            $parentModel = $model->{$attribute}()->getRelated()->newInstance($field);
            $parentModel->save();
            $model->{$attribute}()->associate($parentModel);
        }
    }
}
