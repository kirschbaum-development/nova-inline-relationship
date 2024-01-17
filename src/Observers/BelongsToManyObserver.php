<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Observers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class BelongsToManyObserver extends BaseObserver
{
    /**
     * {@inheritdoc}
     */
    public function updating(Model $model, $attribute, $value)
    {
        $parentModel = $model->{$attribute}();
        $parentModel->sync([]);
        $targetModel  = $parentModel->getRelated();
        foreach($value as $v){
            $row = $targetModel::find($v['modelId']);
            if($row){
                $row->update($v['fields']);
            } else {
                $row = $targetModel::create($v['fields']);
            }
            $parentModel->attach($row[$targetModel->getKeyName()]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function created(Model $model, $attribute, $value)
    {
        $parentModel = $model->{$attribute}();
        $parentModel->sync([]);
        $targetModel  = $parentModel->getRelated();
        foreach($value as $v){
            $row = $targetModel::find($v['modelId']);
            if($row){
                $row->update($v['fields']);
            } else {
                $row = $targetModel::create($v['fields']);
            }
            $parentModel->attach($row[$targetModel->getKeyName()]);
        }
    }
}
