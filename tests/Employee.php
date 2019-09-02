<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /* timestamps not needed it test class */
    public $timestamps = false;

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

    public function teams()
    {
        $this->belongsToMany(Team::class);
    }
}
