<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Exceptions;

use InvalidArgumentException;

class IncorrectRelationshipFormat extends InvalidArgumentException
{
    /**
     * @param string $key
     * @param string $value
     *
     * @return IncorrectRelationshipFormat
     */
    public static function create(string $key, string $value)
    {
        return new static(sprintf('Incorrect relationship attribute value (%s) for a key (%s). Please make sure that array returned by getPropertyMap function is in the following format: "relationship.attribute".', $key, $value));
    }
}
