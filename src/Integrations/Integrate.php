<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Integrations;

use LeadMarvels\Campaigns\Services\QueryClauses\Contracts\WhereInterface;
use KirschbaumDevelopment\NovaInlineRelationship\Integrations\Field as FieldInterface;

class Integrate
{
    public static function fields($object): array
    {
        $basename = class_basename(get_class($object));
        $class = "\\KirschbaumDevelopment\\NovaInlineRelationship\\Integrations\\{$basename}";

        return class_exists($class)
            ? (new $class($object))->fields()
            : (new FieldInterface($object))->fields();
    }
}
