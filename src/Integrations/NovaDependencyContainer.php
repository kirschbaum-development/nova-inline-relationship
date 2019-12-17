<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Integrations;

use KirschbaumDevelopment\NovaInlineRelationship\Integrations\Contracts\ThirdPartyContract;

class NovaDependencyContainer implements ThirdPartyContract
{
    /**
     * @var \Laravel\Nova\Panel
     */
    private $object;

    /**
     * ThirdPartyContract constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Fields array from object.
     *
     * @return array
     */
    public function fields(): array
    {
        return $this->object->meta['fields'];
    }
}
