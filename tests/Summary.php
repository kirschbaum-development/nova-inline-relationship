<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text'];

    public function summarizable()
    {
        return $this->morphTo();
    }
}
