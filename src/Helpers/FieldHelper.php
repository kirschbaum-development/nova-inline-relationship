<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Helpers;

use Illuminate\Http\UploadedFile;

class FieldHelper
{
    /**
     * Generate field
     *
     * @param array $field
     *
     * @return array
     */
    public static function generate(array $field): array
    {
        return array_map(function ($value) {
            if ($value instanceof UploadedFile)
                return $value->hashName();

            return $value;
        }, $field);
    }

    /**
     * Generate bulk field
     *
     * @param array $fields
     *
     * @return array
     */
    public static function generateMany(array $fields): array
    {
        return array_map(function ($field) {
            return self::generate($field);
        }, $fields);
    }
}
