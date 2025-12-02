<?php

declare(strict_types=1);

namespace ModularityFormBuilder\Helper;

class NestedFields
{
    /**
     * Creates a nested array from field data.
     *
     * This function takes an array of field data and converts it into a nested array format.
     * Each element in the input array is transformed into an associative array with 'key' and 'value' keys.
     * The 'key' is obtained by removing the prefix 'id-' and sanitizing the original key.
     *
     * @param array $fieldData The input array of field data.
     * @return array The nested array created from the field data.
     */
    public static function createNestedArrayFromFieldData($fieldData)
    {
        $nestedDataArray = [];
        if (is_array($fieldData)) {
            foreach ($fieldData as $key => $value) {
                $pattern = '/^id-\d+-/';
                $key = preg_replace($pattern, '', sanitize_title($key), 1);
                $nestedDataArray[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        return $nestedDataArray;
    }
}
