<?php

declare(strict_types=1);

namespace DNW\Skills;

/**
 * Parameters about the game for calculating the TrueSkill.
 */
class GameInfo
{
    /**
     * Default initial mean / 6
     */
    private const DEFAULT_BETA = 4.1666666666666666666666666666667;

    private const DEFAULT_DRAW_PROBABILITY = 0.10;

    /**
     * Default initial mean / 300
     */
    private const DEFAULT_DYNAMICS_FACTOR = 0.083333333333333333333333333333333;

    private const DEFAULT_INITIAL_MEAN = 25.0;

    private const DEFAULT_INITIAL_STANDARD_DEVIATION = 8.3333333333333333333333333333333;

    public function __construct(
        private readonly float $initialMean = self::DEFAULT_INITIAL_MEAN,
        private readonly float $initialStandardDeviation = self::DEFAULT_INITIAL_STANDARD_DEVIATION,
        private readonly float $beta = self::DEFAULT_BETA,
        private readonly float $dynamicsFactor = self::DEFAULT_DYNAMICS_FACTOR,
        private readonly float $drawProbability = self::DEFAULT_DRAW_PROBABILITY
    )
    {
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
