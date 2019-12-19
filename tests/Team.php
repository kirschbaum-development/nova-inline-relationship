<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    public function employees()
    {
        $this->belongsToMany(Employee::class);
    }
}
