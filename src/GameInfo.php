<?php

namespace DNW\Skills;

/**
 * Parameters about the game for calculating the TrueSkill.
 */
class GameInfo
{
    private const DEFAULT_BETA = 4.1666666666666666666666666666667; // Default initial mean / 6

    private const DEFAULT_DRAW_PROBABILITY = 0.10;

    private const DEFAULT_DYNAMICS_FACTOR = 0.083333333333333333333333333333333; // Default initial mean / 300

    private const DEFAULT_INITIAL_MEAN = 25.0;

    private const DEFAULT_INITIAL_STANDARD_DEVIATION = 8.3333333333333333333333333333333;

    public function __construct(
        private float $initialMean = self::DEFAULT_INITIAL_MEAN,
        private float $initialStandardDeviation = self::DEFAULT_INITIAL_STANDARD_DEVIATION,
        private float $beta = self::DEFAULT_BETA,
        private float $dynamicsFactor = self::DEFAULT_DYNAMICS_FACTOR,
        private float $drawProbability = self::DEFAULT_DRAW_PROBABILITY
    ) {
    }

    public function getInitialMean(): float
    {
        return $this->initialMean;
    }

    public function getInitialStandardDeviation(): float
    {
        return $this->initialStandardDeviation;
    }

    public function getBeta(): float
    {
        return $this->beta;
    }

    public function getDynamicsFactor(): float
    {
        return $this->dynamicsFactor;
    }

    public function getDrawProbability(): float
    {
        return $this->drawProbability;
    }

    public function getDefaultRating(): Rating
    {
        return new Rating($this->initialMean, $this->initialStandardDeviation);
    }
}
