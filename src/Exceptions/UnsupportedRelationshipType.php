<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Exceptions;

use InvalidArgumentException;

class UnsupportedRelationshipType extends InvalidArgumentException
{
    /**
     * @param string $type
     * @param string $key
     *
     * @return UnsupportedRelationshipType
     */
    public static function create(string $type, string $key)
    {
        return new static("Unsupported Inline Relationship type [{$type}] for an attribute [{$key}].");
    }
}
