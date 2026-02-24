<?php

namespace CommonMy\LaravelCommon\Helpers;

class ArrayHelper
{
    /**
     * Filter and return non-duplicate items in an array based on given keys.
     */
    public static function filterAndReturnNonDuplicates($array, $keys): array
    {
        $seen = [];
        $nonDuplicates = [];
        $filteredArray = [];
        foreach ($array as $item) {
            $hash = '';
            foreach ($keys as $key) {
                $hash .= $item[$key];
            }

            if (!isset($seen[$hash])) {
                $seen[$hash] = true;
                $nonDuplicates[] = $item;
            } else {
                $filteredArray[] = $item;
            }
        }

        return [
            'nonDuplicates' => $nonDuplicates,
            'filteredArray' => $filteredArray,
        ];
    }
}
