<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Contracts;

interface MappableRelationships
{
    /**
     * Should return property map as key value pair.
     *
     * @return array
     */
    public static function getPropertyMap(): array;
}
