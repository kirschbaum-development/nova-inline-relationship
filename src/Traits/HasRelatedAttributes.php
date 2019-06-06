<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
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
     * Checks if a relationship is singular
     *
     * @param $key
     *
     * @return bool
     */
    public function isSingularRelationship($key): bool
    {
        $relation = $this->{$key}();

        return ! ($relation->getResults() instanceof Collection);
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
            $relationships = array_keys(static::getPropertyMap());

            foreach ($relationships as $relationship) {
                $count = count($model->relatedModelAttribs[$relationship]);

                if ($model->isSingularRelationship($relationship)) {
                    $count = 1;
                }

                $models = $model->{$relationship}()->get()->all();

                for ($i = 0; $i < $count; $i++) {
                    if ($i < count($models)) {
                        $models[$i]->update($model->relatedModelAttribs[$relationship][$i]);
                    } else {
                        $model->{$relationship}()->create($model->relatedModelAttribs[$relationship][$i]);
                    }
                }
            }
        });

        static::created(function ($model) {
            $relationships = array_keys(static::getPropertyMap());

            foreach ($relationships as $relationship) {
                if ($model->isSingularRelationship($relationship)) {
                    $model->{$relationship}()->create($model->relatedModelAttribs[$relationship][0]);
                } else {
                    $model->{$relationship}()->createMany($model->relatedModelAttribs[$relationship]);
                }
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
        $propMap = static::getPropertyMap();

        if (Arr::has($propMap, $key)) {
            if ($this->isInvalidRelationship($key)) {
                throw UnsupportedRelationshipType::create($key);
            }

            return $this->{$key}()
                ->get(array_keys($propMap[$key]))
                ->toArray();
        }

        return parent::mutateAttribute($key, $value);
    }

    /**
     * Checks that whether a relationship is invalid.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function isInvalidRelationship($key): bool
    {
        return ! ($this->{$key}() instanceof Relation);
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
            $this->relatedModelAttribs[$key] = $value;
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
