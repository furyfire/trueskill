<?php

namespace DNW\Skills\TrueSkill;

use DNW\Skills\Numerics\GaussianDistribution;

class TruncatedGaussianCorrectionFunctions
{
    // These functions from the bottom of page 4 of the TrueSkill paper.

    /**
     * The "V" function where the team performance difference is greater than the draw margin.
     *
     * In the reference F# implementation, this is referred to as "the additive
     * correction of a single-sided truncated Gaussian with unit variance."
     *
     * @param $teamPerformanceDifference
     * @param  number  $drawMargin In the paper, it's referred to as just "ε".
     * @param $c
     */
    public static function vExceedsMarginScaled($teamPerformanceDifference, float|int $drawMargin, $c): float
    {
        return self::vExceedsMargin($teamPerformanceDifference / $c, $drawMargin / $c);
    }

    public static function vExceedsMargin($teamPerformanceDifference, $drawMargin)
    {
        $denominator = GaussianDistribution::cumulativeTo($teamPerformanceDifference - $drawMargin);

        if ($denominator < 2.222758749e-162) {
            return -$teamPerformanceDifference + $drawMargin;
        }

        return GaussianDistribution::at($teamPerformanceDifference - $drawMargin) / $denominator;
    }

    /**
     * The "W" function where the team performance difference is greater than the draw margin.
     *
     * In the reference F# implementation, this is referred to as "the multiplicative
     * correction of a single-sided truncated Gaussian with unit variance."
     *
     * @param $teamPerformanceDifference
     * @param $drawMargin
     * @param $c
     */
    public static function wExceedsMarginScaled($teamPerformanceDifference, float|int $drawMargin, $c): float
    {
        return self::wExceedsMargin($teamPerformanceDifference / $c, $drawMargin / $c);
    }

    public static function wExceedsMargin($teamPerformanceDifference, $drawMargin): float
    {
        $denominator = GaussianDistribution::cumulativeTo($teamPerformanceDifference - $drawMargin);

        if ($denominator < 2.222758749e-162) {
            if ($teamPerformanceDifference < 0.0) {
                return 1.0;
            }

            return 0.0;
        }

        $vWin = self::vExceedsMargin($teamPerformanceDifference, $drawMargin);

        return $vWin * ($vWin + $teamPerformanceDifference - $drawMargin);
    }

    // the additive correction of a double-sided truncated Gaussian with unit variance
    public static function vWithinMarginScaled($teamPerformanceDifference, float|int $drawMargin, $c): float
    {
        return self::vWithinMargin($teamPerformanceDifference / $c, $drawMargin / $c);
    }

    // from F#:
    public static function vWithinMargin($teamPerformanceDifference, $drawMargin): float
    {
        $teamPerformanceDifferenceAbsoluteValue = abs($teamPerformanceDifference);
        $denominator =
            GaussianDistribution::cumulativeTo($drawMargin - $teamPerformanceDifferenceAbsoluteValue) -
            GaussianDistribution::cumulativeTo(-$drawMargin - $teamPerformanceDifferenceAbsoluteValue);

        if ($denominator < 2.222758749e-162) {
            if ($teamPerformanceDifference < 0.0) {
                return -$teamPerformanceDifference - $drawMargin;
            }

            return -$teamPerformanceDifference + $drawMargin;
        }

        $numerator = GaussianDistribution::at(-$drawMargin - $teamPerformanceDifferenceAbsoluteValue) -
            GaussianDistribution::at($drawMargin - $teamPerformanceDifferenceAbsoluteValue);

        if ($teamPerformanceDifference < 0.0) {
            return -$numerator / $denominator;
        }

        return $numerator / $denominator;
    }

    // the multiplicative correction of a double-sided truncated Gaussian with unit variance
    public static function wWithinMarginScaled($teamPerformanceDifference, float|int $drawMargin, $c): float
    {
        return self::wWithinMargin($teamPerformanceDifference / $c, $drawMargin / $c);
    }

    // From F#:
    public static function wWithinMargin($teamPerformanceDifference, float|int $drawMargin)
    {
        $teamPerformanceDifferenceAbsoluteValue = abs($teamPerformanceDifference);
        $denominator = GaussianDistribution::cumulativeTo($drawMargin - $teamPerformanceDifferenceAbsoluteValue)
            -
            GaussianDistribution::cumulativeTo(-$drawMargin - $teamPerformanceDifferenceAbsoluteValue);

        if ($denominator < 2.222758749e-162) {
            return 1.0;
        }

        $vt = self::vWithinMargin($teamPerformanceDifferenceAbsoluteValue, $drawMargin);

        return $vt * $vt +
        (
            ($drawMargin - $teamPerformanceDifferenceAbsoluteValue)
            *
            GaussianDistribution::at(
                $drawMargin - $teamPerformanceDifferenceAbsoluteValue)
            - (-$drawMargin - $teamPerformanceDifferenceAbsoluteValue)
            *
            GaussianDistribution::at(-$drawMargin - $teamPerformanceDifferenceAbsoluteValue)) / $denominator;
    }
}
