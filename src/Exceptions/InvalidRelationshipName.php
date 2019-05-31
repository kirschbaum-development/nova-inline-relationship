<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Exceptions;

use InvalidArgumentException;

class InvalidRelationshipName extends InvalidArgumentException
{
    /**
     * @param string $key
     * @param string $value
     *
     * @return InvalidRelationshipName
     */
    public static function create(string $key, string $value)
    {
        return new static(sprintf('Invalid relationship name (%s) for a key (%s) in propertyMap array. Please make sure that this relationship is defined on the model', $value, $key));
    }
}
