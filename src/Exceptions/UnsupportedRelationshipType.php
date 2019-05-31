<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Exceptions;

use InvalidArgumentException;

class UnsupportedRelationshipType extends InvalidArgumentException
{
    /**
     * @param string $key
     *
     * @return UnsupportedRelationshipType
     */
    public static function create(string $key)
    {
        return new static(sprintf('Unsupported Relationship type for a key (%s) in array returned by getPropertyMap function. This package currently supports singular relationships only.', $key));
    }
}
