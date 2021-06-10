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
        return collect($field)
            ->map(function ($value) {
                if ($value instanceof UploadedFile)
                    return $value->hashName();

                return $value;
            })
            ->toArray();
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
        return collect($fields)
            ->map(function ($field) {
                return self::generate($field);
            })
            ->toArray();
    }
}
