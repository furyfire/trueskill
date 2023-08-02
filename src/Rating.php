<?php

namespace DNW\Skills;

// Container for a player's rating.
use DNW\Skills\Numerics\GaussianDistribution;

class Rating implements \Stringable
{
    private const CONSERVATIVE_STANDARD_DEVIATION_MULTIPLIER = 3;

    /**
     * Constructs a rating.
     *
     * @param float     $_mean                                    The statistical mean value of the rating (also known as mu).
     * @param float     $_standardDeviation                       The standard deviation of the rating (also known as s).
     * @param float|int $_conservativeStandardDeviationMultiplier optional The number of standardDeviations to subtract from the mean to achieve a conservative rating.
     */
    public function __construct(private float $_mean, private float $_standardDeviation, private float|int $_conservativeStandardDeviationMultiplier = self::CONSERVATIVE_STANDARD_DEVIATION_MULTIPLIER)
    {
    }

    /**
     * The statistical mean value of the rating (also known as mu).
     */
    public function getMean(): float
    {
        return $this->_mean;
    }

    /**
     * The standard deviation (the spread) of the rating. This is also known as s.
     */
    public function getStandardDeviation(): float
    {
        return $this->_standardDeviation;
    }

    /**
     * A conservative estimate of skill based on the mean and standard deviation.
     */
    public function getConservativeRating(): float
    {
        return $this->_mean - $this->_conservativeStandardDeviationMultiplier * $this->_standardDeviation;
    }

    public function getPartialUpdate(Rating $prior, Rating $fullPosterior, $updatePercentage): Rating
    {
        $priorGaussian = new GaussianDistribution($prior->getMean(), $prior->getStandardDeviation());
        $posteriorGaussian = new GaussianDistribution($fullPosterior->getMean(), $fullPosterior . getStandardDeviation());

        // From a clarification email from Ralf Herbrich:
        // "the idea is to compute a linear interpolation between the prior and posterior skills of each player
        //  ... in the canonical space of parameters"

        $precisionDifference = $posteriorGaussian->getPrecision() - $priorGaussian->getPrecision();
        $partialPrecisionDifference = $updatePercentage * $precisionDifference;

        $precisionMeanDifference = $posteriorGaussian->getPrecisionMean() - $priorGaussian->getPrecisionMean();
        $partialPrecisionMeanDifference = $updatePercentage * $precisionMeanDifference;

        $partialPosteriorGaussion = GaussianDistribution::fromPrecisionMean(
            $priorGaussian->getPrecisionMean() + $partialPrecisionMeanDifference,
            $priorGaussian->getPrecision() + $partialPrecisionDifference
        );

        return new Rating($partialPosteriorGaussion->getMean(), $partialPosteriorGaussion->getStandardDeviation(), $prior->_conservativeStandardDeviationMultiplier);
    }

    public function __toString(): string
    {
        return sprintf('mean=%.4f, standardDeviation=%.4f', $this->_mean, $this->_standardDeviation);
    }
}
