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
        private $_initialMean = self::DEFAULT_INITIAL_MEAN,
        private $_initialStandardDeviation = self::DEFAULT_INITIAL_STANDARD_DEVIATION,
        private $_beta = self::DEFAULT_BETA,
        private $_dynamicsFactor = self::DEFAULT_DYNAMICS_FACTOR,
        private $_drawProbability = self::DEFAULT_DRAW_PROBABILITY
    ) {
    }

    public function getInitialMean()
    {
        return $this->_initialMean;
    }

    public function getInitialStandardDeviation()
    {
        return $this->_initialStandardDeviation;
    }

    public function getBeta()
    {
        return $this->_beta;
    }

    public function getDynamicsFactor()
    {
        return $this->_dynamicsFactor;
    }

    public function getDrawProbability()
    {
        return $this->_drawProbability;
    }

    public function getDefaultRating()
    {
        return new Rating($this->_initialMean, $this->_initialStandardDeviation);
    }
}
