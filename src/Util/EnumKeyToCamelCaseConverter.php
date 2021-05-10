<?php

namespace MNGame\Util;

use RuntimeException;

class EnumKeyToCamelCaseConverter
{
    /**
     * @throws RuntimeException
     */
    public static function getCamelCase(string $string): string {
        $exploded = explode('_', $string);

        if (!$exploded) {
            throw new RuntimeException('Can not explode text ' . $string);
        }

        foreach ($exploded as $item) {
            $result = ($result ?? '') . ucfirst(strtolower($item));
        }

        return $result ?? '';
    }
}