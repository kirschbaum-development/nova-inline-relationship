<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
