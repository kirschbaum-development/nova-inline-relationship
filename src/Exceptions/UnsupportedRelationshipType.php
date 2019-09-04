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
        return new static(sprintf('Unsupported Inline Relationship type (%s) for an attribute (%s).', $type, $key));
    }
}
