<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Exceptions;

use InvalidArgumentException;

class UnsupportedNestedRelationship extends InvalidArgumentException
{
    /**
     * @param string $key
     * @param string $value
     *
     * @return UnsupportedNestedRelationship
     */
    public static function create(string $key, string $value)
    {
        return new static(
            "Unsupported nested relationship attribute value [{$value}] for a key [{$key}]. Please make sure that " .
            'array returned by getPropertyMap function is in the following format: "relationship.attribute" and is ' .
            'only one level deep.'
        );
    }
}
