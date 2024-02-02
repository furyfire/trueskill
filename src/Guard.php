<?php

namespace DNW\Skills;

use Exception;

/**
 * Verifies argument contracts.
 *
 * @see http://www.moserware.com/2008/01/borrowing-ideas-from-3-interesting.html
 */
class Guard
{
    public static function argumentNotNull(mixed $value, string $parameterName): void
    {
        if ($value == NULL) {
            throw new Exception($parameterName . ' can not be null');
        }
    }

    public static function argumentIsValidIndex(int $index, int $count, string $parameterName): void
    {
        if (($index < 0) || ($index >= $count)) {
            throw new Exception($parameterName . ' is an invalid index');
        }
    }

    public static function argumentInRangeInclusive(float $value, float $min, float $max, string $parameterName): void
    {
        if (($value < $min) || ($value > $max)) {
            throw new Exception($parameterName . ' is not in the valid range [' . $min . ', ' . $max . ']');
        }
    }
}
