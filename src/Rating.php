<?php

declare(strict_types=1);

namespace DNW\Skills;

use DNW\Skills\Numerics\GaussianDistribution;

/**
 * Container for a player's rating.
 */
class Rating implements \Stringable
{
    private const CONSERVATIVE_STANDARD_DEVIATION_MULTIPLIER = 3;

    /**
     * Constructs a rating.
     *
     * @param float     $mean                                    The statistical mean value of the rating (also known as mu).
     * @param float     $standardDeviation                       The standard deviation of the rating (also known as s).
     * @param float|int $conservativeStandardDeviationMultiplier optional The number of standardDeviations to subtract from the mean to achieve a conservative rating.
     */
    public function __construct(private readonly float $mean, private readonly float $standardDeviation, private readonly float|int $conservativeStandardDeviationMultiplier = self::CONSERVATIVE_STANDARD_DEVIATION_MULTIPLIER)
    {
    }

    /**
     * The statistical mean value of the rating (also known as mu).
     */
    public function getMean(): float
    {
        return $this->mean;
    }

    /**
     * The standard deviation (the spread) of the rating. This is also known as s.
     */
    public function getStandardDeviation(): float
    {
        return $this->standardDeviation;
    }

    /**
     * A conservative estimate of skill based on the mean and standard deviation.
     */
    public function getConservativeRating(): float
    {
        return $this->mean - $this->conservativeStandardDeviationMultiplier * $this->standardDeviation;
    }

    /**
     * Get a partial rating update.
     */
    public function getPartialUpdate(Rating $prior, Rating $fullPosterior, float $updatePercentage): Rating
    {
        $priorGaussian = new GaussianDistribution($prior->getMean(), $prior->getStandardDeviation());
        $posteriorGaussian = new GaussianDistribution($fullPosterior->getMean(), $fullPosterior->getStandardDeviation());

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

        return new Rating($partialPosteriorGaussion->getMean(), $partialPosteriorGaussion->getStandardDeviation(), $prior->conservativeStandardDeviationMultiplier);
    }

    public function __toString(): string
    {
        return sprintf('mean=%.4f, standardDeviation=%.4f', $this->mean, $this->standardDeviation);
    }
}
