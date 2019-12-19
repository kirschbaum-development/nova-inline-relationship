<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['phone'];

    public function employee()
    {
        $this->belongsTo(Employee::class);
    }
}
