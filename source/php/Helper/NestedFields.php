<?php

namespace ModularityFormBuilder\Helper;

class NestedFields
{

    public static function createNestedArrayFromFieldData($fieldData)
    {
        $nestedDataArray = [];
        if (is_array($fieldData)) {
            foreach ($fieldData as $key => $value) {
                $pattern = '/^id-\d+-/';
                $key = preg_replace($pattern, "", sanitize_title($key), 1);
                $nestedDataArray[] = [
                    'key' => $key,
                    'value' => $value
                ];
            }
        }

        return $nestedDataArray;
    }
}
