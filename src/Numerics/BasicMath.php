<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

/**
 * Basic math functions.
 *
 * @author    Jeff Moser <jeff@moserware.com>
 * @copyright 2010 Jeff Moser
 */
class BasicMath
{
    /**
     * Squares the input (x^2 = x * x)
     *
     * @param $x Value to square (x)
     *
     * @return float The squared value (x^2)
     */
    public static function square(float $x): float
    {
        return $x * $x;
    }

    /**
     * Sums the items in $itemsToSum
     *
     * @param mixed[]    $itemsToSum The items to sum,
     * @param \Closure $callback   The function to apply to each array element before summing.
     *
     * @return float The sum.
     */
    public static function sum(array $itemsToSum, \Closure $callback): float
    {
        $mappedItems = array_map($callback, $itemsToSum);

        return array_sum($mappedItems);
    }
}
