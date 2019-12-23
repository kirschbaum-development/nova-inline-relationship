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
        return new static(
            "Invalid relationship name [{$value}] for a key [{$key}] in array returned by getPropertyMap function. " .
            'Please make sure that this relationship is defined on the model.'
        );
    }
}
