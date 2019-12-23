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
        return new static(
            "Incorrect relationship attribute value [{$value}] for a key [{$key}]. Please make sure that array " .
            'returned by getPropertyMap function is in the following format: "relationship.attribute".'
        );
    }
}
