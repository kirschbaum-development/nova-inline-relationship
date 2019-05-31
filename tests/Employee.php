<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Tests;

use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaInlineRelationship\Traits\HasRelatedAttributes;
use KirschbaumDevelopment\NovaInlineRelationship\Contracts\MappableRelationships;

class Employee extends Model implements MappableRelationships
{
    use HasRelatedAttributes;

    /* timestamps not needed it test class */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Should return property map as key value pair.
     *
     * @return array
     */
    public static function getPropertyMap(): array
    {
        return [];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
