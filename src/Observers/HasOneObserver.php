<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Helpers\FieldHelper;

class HasOneObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $childModel = $model->{$attribute}()->first();
        $field = FieldHelper::generate($value[0]['fields']);

        if (! empty($childModel)) {
            count($value)
                ? $childModel->update($field)
                : $childModel->delete();
        } elseif (count($value)) {
            $model->{$attribute}()->create($field);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        if (count($value)) {
            $field = FieldHelper::generate($value[0]['fields']);
            $model->{$attribute}()->create($field);
        }
    }
}
