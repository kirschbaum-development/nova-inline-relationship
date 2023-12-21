<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Fluent extends \Illuminate\Support\Fluent
{
    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     *
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $attribute = Str::replace('->', '.', $key);

            if (! Arr::has($this->attributes, $attribute)) {
                Arr::set($this->attributes, $attribute, $value);
            }
        }

        return $this;
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     *
     * @return $this
     */
    public function forceFill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $attribute = Str::replace('->', '.', $key);

            Arr::set($this->attributes, $attribute, $value);
        }

        return $this;
    }
}
