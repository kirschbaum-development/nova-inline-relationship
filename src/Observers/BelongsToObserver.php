<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BelongsToObserver extends BaseObserver
{
    /**
     * {@inheritdoc} Doc did not inherit ... :)
     */
    public function updating(Model $model, $attribute, $value)
    {
        // @note: why first() ?
        $parentModel = $model->{$attribute}()->first();

        if (empty($parentModel)) {
            return $this->creating($model, $attribute, $value);
        }

        // @note: what about multiple $models updating? MorphToMany etc?
        if (count($value)) {
            $parentModel->update($value[0]);
        }
    }

    /**
     * {@inheritdoc} Doc did not inherit ... :)
     */
    public function creating(Model $model, $attribute, $value)
    {
        if (count($value)) {
            // @note same as updating.
            $parentModel = $model->{$attribute}()->getRelated()->newInstance($value[0]);
            $parentModel->save();
            $model->{$attribute}()->associate($parentModel);
        }
    }
}
