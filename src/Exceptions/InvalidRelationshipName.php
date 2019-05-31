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
        return new static(sprintf('You have added an invalid relationship named `%s` for key `%s` in propsMap. Please make sure that this relationship exists for this model.', $value, $key));
    }
}
