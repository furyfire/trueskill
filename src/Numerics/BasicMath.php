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
     * Squares the input (input^2 = input * input)
     *
     * @param $input Value to square (input)
     *
     * @return float The squared value (input^2)
     */
    public static function square(float $input): float
    {
        return $input * $input;
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
