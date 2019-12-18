<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function summary()
    {
        return $this->morphOne(Summary::class, 'summarizable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
