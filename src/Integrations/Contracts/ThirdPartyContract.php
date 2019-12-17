<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Integrations\Contracts;

interface ThirdPartyContract
{
    /**
     * ThirdPartyContract constructor.
     *
     * @param $object
     */
    public function __construct($object);

    /**
     * Fields array from object.
     *
     * @return array
     */
    public function fields(): array;
}
