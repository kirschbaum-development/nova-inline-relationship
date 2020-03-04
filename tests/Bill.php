<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'weight'];

    public function employee()
    {
        $this->belongsTo(Employee::class);
    }
}
