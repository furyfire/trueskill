<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

/**
 * Computes Gaussian (bell curve) values.
 *
 * @author    Jeff Moser <jeff@moserware.com>
 * @copyright 2010 Jeff Moser
 */
class GaussianDistribution implements \Stringable
{
    //sqrt(2*pi)
    //from https://www.wolframalpha.com/input?i=sqrt%282*pi%29
    private const M_SQRT_2_PI = 2.5066282746310005024157652848110452530069867406099383166299235763;
    
    //log(sqrt(2*pi))
    //From https://www.wolframalpha.com/input?i=log%28sqrt%282*pi%29%29
    private const M_LOG_SQRT_2_PI = 0.9189385332046727417803297364056176398613974736377834128171515404;
    // precision and precisionMean are used because they make multiplying and dividing simpler
    // (the the accompanying math paper for more details)
    private float $precision;

    private float $precisionMean;

    private float $variance;

    public function __construct(private float $mean = 0.0, private float $standardDeviation = 1.0)
    {
        $this->variance = BasicMath::square($standardDeviation);

        if ($this->variance != 0) {
            $this->precision = 1.0 / $this->variance;
            $this->precisionMean = $this->precision * $this->mean;
        } else {
            $this->precision = \INF;

            $this->precisionMean = $this->mean == 0 ? 0 : \INF;
        }
    }

    public function getMean(): float
    {
        return $this->mean;
    }

    public function getVariance(): float
    {
        return $this->variance;
    }

    public function getStandardDeviation(): float
    {
        return $this->standardDeviation;
    }

    public function getPrecision(): float
    {
        return $this->precision;
    }

    public function getPrecisionMean(): float
    {
        return $this->precisionMean;
    }

    public function getNormalizationConstant(): float
    {
        // Great derivation of this is at http://www.astro.psu.edu/~mce/A451_2/A451/downloads/notes0.pdf
        return 1.0 / (self::M_SQRT_2_PI * $this->standardDeviation);
    }

    public static function fromPrecisionMean(float $precisionMean, float $precision): self
    {
        $result = new GaussianDistribution();
        $result->precision = $precision;
        $result->precisionMean = $precisionMean;

        if ($precision != 0) {
            $result->variance = 1.0 / $precision;
            $result->standardDeviation = sqrt($result->variance);
            $result->mean = $result->precisionMean / $result->precision;
        } else {
            $result->variance = \INF;
            $result->standardDeviation = \INF;
            $result->mean = \NAN;
        }

        return $result;
    }

    // For details, see http://www.tina-vision.net/tina-knoppix/tina-memo/2003-003.pdf
    // for multiplication, the precision mean ones are easier to write :)
    public static function multiply(GaussianDistribution $left, GaussianDistribution $right): self
    {
        return GaussianDistribution::fromPrecisionMean($left->precisionMean + $right->precisionMean, $left->precision + $right->precision);
    }

    // Computes the absolute difference between two Gaussians
    public static function absoluteDifference(GaussianDistribution $left, GaussianDistribution $right): float
    {
        return max(
            abs($left->precisionMean - $right->precisionMean),
            sqrt(abs($left->precision - $right->precision))
        );
    }

    // Computes the absolute difference between two Gaussians
    public static function subtract(GaussianDistribution $left, GaussianDistribution $right): float
    {
        return GaussianDistribution::absoluteDifference($left, $right);
    }

    public static function logProductNormalization(GaussianDistribution $left, GaussianDistribution $right): float
    {
        if (($left->precision == 0) || ($right->precision == 0)) {
            return 0;
        }

        $varianceSum = $left->variance + $right->variance;
        $meanDifference = $left->mean - $right->mean;

        return -self::M_LOG_SQRT_2_PI - (log($varianceSum) / 2.0) - (BasicMath::square($meanDifference) / (2.0 * $varianceSum));
    }

    public static function divide(GaussianDistribution $numerator, GaussianDistribution $denominator): self
    {
        return GaussianDistribution::fromPrecisionMean(
            $numerator->precisionMean - $denominator->precisionMean,
            $numerator->precision - $denominator->precision
        );
    }

    public static function logRatioNormalization(GaussianDistribution $numerator, GaussianDistribution $denominator): float
    {
        if (($numerator->precision == 0) || ($denominator->precision == 0)) {
            return 0;
        }

        $varianceDifference = $denominator->variance - $numerator->variance;
        $meanDifference = $numerator->mean - $denominator->mean;

        return log($denominator->variance) + self::M_LOG_SQRT_2_PI - log($varianceDifference) / 2.0 +
        BasicMath::square($meanDifference) / (2 * $varianceDifference);
    }

    public static function at(float $x, float $mean = 0.0, float $standardDeviation = 1.0): float
    {
        // See http://mathworld.wolfram.com/NormalDistribution.html
        //                1              -(x-mean)^2 / (2*stdDev^2)
        // P(x) = ------------------- * e
        //        stdDev * sqrt(2*pi)

        $multiplier = 1.0 / ($standardDeviation * self::M_SQRT_2_PI);
        $expPart = exp((-1.0 * BasicMath::square($x - $mean)) / (2 * BasicMath::square($standardDeviation)));

        return $multiplier * $expPart;
    }

    public static function cumulativeTo(float $x, float $mean = 0.0, float $standardDeviation = 1.0): float
    {
        $result = GaussianDistribution::errorFunctionCumulativeTo(-M_SQRT1_2 * $x);

        return 0.5 * $result;
    }

    private static function errorFunctionCumulativeTo(float $x): float
    {
        // Derived from page 265 of Numerical Recipes 3rd Edition
        $z = abs($x);

        $t = 2.0 / (2.0 + $z);
        $ty = 4 * $t - 2;

        $coefficients = [
            -1.3026537197817094,
            6.4196979235649026e-1,
            1.9476473204185836e-2,
            -9.561514786808631e-3,
            -9.46595344482036e-4,
            3.66839497852761e-4,
            4.2523324806907e-5,
            -2.0278578112534e-5,
            -1.624290004647e-6,
            1.303655835580e-6,
            1.5626441722e-8,
            -8.5238095915e-8,
            6.529054439e-9,
            5.059343495e-9,
            -9.91364156e-10,
            -2.27365122e-10,
            9.6467911e-11,
            2.394038e-12,
            -6.886027e-12,
            8.94487e-13,
            3.13092e-13,
            -1.12708e-13,
            3.81e-16,
            7.106e-15,
            -1.523e-15,
            -9.4e-17,
            1.21e-16,
            -2.8e-17,
        ];

        $ncof = count($coefficients);
        $d = 0.0;
        $dd = 0.0;

        for ($j = $ncof - 1; $j > 0; $j--) {
            $tmp = $d;
            $d = $ty * $d - $dd + $coefficients[$j];
            $dd = $tmp;
        }

        $ans = $t * exp(-$z * $z + 0.5 * ($coefficients[0] + $ty * $d) - $dd);

        return ($x >= 0.0) ? $ans : (2.0 - $ans);
    }

    private static function inverseErrorFunctionCumulativeTo(float $p): float
    {
        // From page 265 of numerical recipes

        if ($p >= 2.0) {
            return -100;
        }
        if ($p <= 0.0) {
            return 100;
        }

        $pp = ($p < 1.0) ? $p : 2 - $p;
        $t = sqrt(-2 * log($pp / 2.0)); // Initial guess
        $x = -M_SQRT1_2 * ((2.30753 + $t * 0.27061) / (1.0 + $t * (0.99229 + $t * 0.04481)) - $t);

        for ($j = 0; $j < 2; $j++) {
            $err = GaussianDistribution::errorFunctionCumulativeTo($x) - $pp;
            $x += $err / (M_2_SQRTPI * exp(-BasicMath::square($x)) - $x * $err); // Halley
        }

        return ($p < 1.0) ? $x : -$x;
    }

    public static function inverseCumulativeTo(float $x, float $mean = 0.0, float $standardDeviation = 1.0): float
    {
        // From numerical recipes, page 320
        return $mean - M_SQRT2 * $standardDeviation * GaussianDistribution::inverseErrorFunctionCumulativeTo(2 * $x);
    }

    public function __toString(): string
    {
        return sprintf('mean=%.4f standardDeviation=%.4f', $this->mean, $this->standardDeviation);
    }
}
