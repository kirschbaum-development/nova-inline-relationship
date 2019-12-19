<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
