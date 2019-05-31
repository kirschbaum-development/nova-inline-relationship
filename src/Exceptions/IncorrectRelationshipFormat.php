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
        return new static(sprintf('You have added an incorrect relationship attribute value `%s` for key `%s`. Please make sure that your added values must have two parts and should be in `relationship.attribute` format.', $key, $value));
    }
}
