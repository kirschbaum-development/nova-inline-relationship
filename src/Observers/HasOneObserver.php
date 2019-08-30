<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;

class HasOneObserver extends BaseObserver
{
    public function updating(Model $model, $attribute, $value)
    {
        $childModel = $model->{$attribute}()->first();

        if (! empty($childModel)) {
            if (count($value)) {
                $childModel->update($value[0]);
            } else {
                $childModel->delete();
            }
        } else {
            $model->{$attribute}()->create($value[0]);
        }
    }

    public function created(Model $model, $attribute, $value)
    {
        $model->{$attribute}()->create($value[0]);
    }
}
