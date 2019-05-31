<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Contracts;

interface RelationshipMappable
{
    /**
     * Should return property map as key value pair.
     *
     * @return array
     */
    public static function getPropertyMap(): array;
}
