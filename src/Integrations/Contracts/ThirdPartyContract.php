<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Integrations\Contracts;

interface ThirdPartyContract
{
    /**
     * ThirdPartyContract constructor.
     *
     * @param $field
     */
    public function __construct($field);

    /**
     * Fields array from object.
     *
     * @return array
     */
    public function fields(): array;
}
