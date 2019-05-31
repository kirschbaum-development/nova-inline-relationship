<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\InvalidRelationshipName;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\IncorrectRelationshipFormat;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\UnsupportedRelationshipType;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\UnsupportedNestedRelationship;

trait HasRelatedAttributes
{
    /** @var array */
    protected $relatedModelAttribs = [];

    /** @var bool */
    protected $isDirty = false;

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function hasSetMutator($key)
    {
        if (Arr::has(static::getPropertyMap(), $key)) {
            return true;
        }

        return parent::hasSetMutator($key);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function hasGetMutator($key)
    {
        if (Arr::has(static::getPropertyMap(), $key)) {
            return true;
        }

        return parent::hasGetMutator($key);
    }

    /**
     * Should return property map as key value pair.
     *
     * @return array
     */
    public static function getPropertyMap(): array
    {
        return [];
    }

    /**
     * Returns a unique array of relationships available in map.
     *
     * @return array
     */
    protected static function getUniqueRelationships(): array
    {
        return collect(static::getPropertyMap())->map(function ($item, $key) {
            return static::getRelatedPropertyParts($key)['relationship'];
        })->filter()->unique()->values()->all();
    }

    /**
     * Trait Boot Function for hooking in Updating and created events.
     */
    protected static function bootHasRelatedAttributes()
    {
        Model::boot();

        static::updating(function ($model) {
            $relationships = static::getUniqueRelationships();

            foreach ($relationships as $relationship) {
                if (! $model->{$relationship}) {
                    $model->{$relationship}()->create($model->relatedModelAttribs[$relationship]);
                } else {
                    $model->{$relationship}->save();
                }
            }
        });

        static::created(function ($model) {
            $relationships = static::getUniqueRelationships();

            foreach ($relationships as $relationship) {
                $model->{$relationship}()->create($model->relatedModelAttribs[$relationship]);
            }
        });
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        if (Arr::has(static::getPropertyMap(), $key)) {
            $property = static::getRelatedPropertyParts($key);

            if ($this->isInvalidRelationship($property['relationship'])) {
                throw UnsupportedRelationshipType::create($key);
            }

            return optional($this->{$property['relationship']})->{$property['attribute']};
        }

        return parent::mutateAttribute($key, $value);
    }

    /**
     * Checks that whether a relationship is invalid.
     * Currently relationships returning collections are unsupported.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function isInvalidRelationship($key): bool
    {
        return $this->{$key} instanceof Collection;
    }

    /**
     * Search for properties listed in propsMap and return as an array.
     *
     * @param string $key
     *
     * @return array
     */
    protected static function getRelatedPropertyParts($key): array
    {
        $value = Arr::get(static::getPropertyMap(), $key);
        $parts = explode('.', $value);

        if (count($parts) == 1) {
            throw IncorrectRelationshipFormat::create($key, $value);
        } elseif (count($parts) >= 3) {
            throw UnsupportedNestedRelationship::create($key, $value);
        }

        if (! method_exists(static::class, $parts[0])) {
            throw InvalidRelationshipName::create($key, $parts[0]);
        }

        return [
            'relationship' => $parts[0],
            'attribute' => $parts[1],
        ];
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function setMutatedAttributeValue($key, $value)
    {
        if (Arr::has(static::getPropertyMap(), $key)) {
            $property = static::getRelatedPropertyParts($key);

            if ($this->isInvalidRelationship($property['relationship'])) {
                throw UnsupportedRelationshipType::create($key);
            }

            if (! $this->{$property['relationship']}) {
                $this->relatedModelAttribs[$property['relationship']][$property['attribute']] = $value;
            } else {
                $this->{$property['relationship']}->{$property['attribute']} = $value;
            }

            $this->isDirty = true;

            return true;
        }

        return parent::setMutatedAttributeValue($key, $value);
    }

    /**
     * Determine if any of the given attributes were changed.
     *
     * @param  array  $changes
     * @param  array|string|null  $attributes
     *
     * @return bool
     */
    protected function hasChanges($changes, $attributes = null)
    {
        if ($this->isDirty) {
            return true;
        }

        return parent::hasChanges($changes, $attributes);
    }
}
