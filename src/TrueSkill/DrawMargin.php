<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill;

use DNW\Skills\Numerics\GaussianDistribution;

final class DrawMargin
{
    public static function getDrawMarginFromDrawProbability(float $drawProbability, float $beta): float
    {
        // Derived from TrueSkill technical report (MSR-TR-2006-80), page 6

        // draw probability = 2 * CDF(margin/(sqrt(n1+n2)*beta)) -1

        // implies
        //
        // margin = inversecdf((draw probability + 1)/2) * sqrt(n1+n2) * beta
        // n1 and n2 are the number of players on each team
        return GaussianDistribution::inverseCumulativeTo(0.5 * ($drawProbability + 1.0), 0.0, 1.0) * M_SQRT2 * $beta;
    }
}
