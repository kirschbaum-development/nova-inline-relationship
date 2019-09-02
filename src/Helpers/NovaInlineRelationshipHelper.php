<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Helpers;

class NovaInlineRelationshipHelper
{
    /**
     * Returns Observer Classname for a relationship
     *
     * @param $relationship
     *
     * @return string
     */
    public static function getObserver($relationship)
    {
        return '\\KirschbaumDevelopment\\NovaInlineRelationship\\Observers\\' . class_basename($relationship) . 'Observer';
    }
}
