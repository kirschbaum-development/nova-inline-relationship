<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text'];

    public function commentable()
    {
        return $this->morphTo();
    }
}
