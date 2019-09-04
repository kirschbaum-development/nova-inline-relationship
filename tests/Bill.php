<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount'];

    public function employee()
    {
        $this->belongsTo(Employee::class);
    }
}
