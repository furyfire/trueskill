<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\Numerics;

use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Numerics\GaussianDistribution;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GaussianDistribution::class)]
#[UsesClass(BasicMath::class)]
final class GaussianDistributionTest extends TestCase
{
    private const float ERROR_TOLERANCE = 0.000001;

    public function testGetters(): void
    {
        $gd = new GaussianDistribution(10, 3);

        $this->assertEquals(10, $gd->getMean());
        $this->assertEquals(9, $gd->getVariance());
        $this->assertEquals(3, $gd->getStandardDeviation());
        $this->assertEquals(1 / 9, $gd->getPrecision());
        $this->assertEquals(1.0 / 9.0 * 10.0, $gd->getPrecisionMean());
        $this->assertEqualsWithDelta(0.13298076013, $gd->getNormalizationConstant(), GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testCumulativeTo(): void
    {
        // Verified with WolframAlpha
        // (e.g. http://www.wolframalpha.com/input/?i=CDF%5BNormalDistribution%5B0%2C1%5D%2C+0.5%5D )
        $this->assertEqualsWithDelta(0.691462, GaussianDistribution::cumulativeTo(0.5), GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testAt(): void
    {
        // Verified with WolframAlpha
        // (e.g. http://www.wolframalpha.com/input/?i=PDF%5BNormalDistribution%5B0%2C1%5D%2C+0.5%5D )
        $this->assertEqualsWithDelta(0.352065, GaussianDistribution::at(0.5), GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testMultiplication(): void
    {
        // I verified this against the formula at http://www.tina-vision.net/tina-knoppix/tina-memo/2003-003.pdf
        $standardNormal  = new GaussianDistribution(0, 1);
        $shiftedGaussian = new GaussianDistribution(2, 3);
        $product         = GaussianDistribution::multiply($standardNormal, $shiftedGaussian);

        $this->assertEqualsWithDelta(0.2, $product->getMean(), GaussianDistributionTest::ERROR_TOLERANCE);
        $this->assertEqualsWithDelta(3.0 / sqrt(10), $product->getStandardDeviation(), GaussianDistributionTest::ERROR_TOLERANCE);

        $m4s5 = new GaussianDistribution(4, 5);
        $m6s7 = new GaussianDistribution(6, 7);

        $product2 = GaussianDistribution::multiply($m4s5, $m6s7);

        $expectedMean = (4.0 * BasicMath::square(7) + 6.0 * BasicMath::square(5)) / (BasicMath::square(5) + BasicMath::square(7));
        $this->assertEqualsWithDelta($expectedMean, $product2->getMean(), GaussianDistributionTest::ERROR_TOLERANCE);

        $expectedSigma = sqrt(((BasicMath::square(5) * BasicMath::square(7)) / (BasicMath::square(5) + BasicMath::square(7))));
        $this->assertEqualsWithDelta($expectedSigma, $product2->getStandardDeviation(), GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testDivision(): void
    {
        // Since the multiplication was worked out by hand, we use the same numbers but work backwards
        $product        = new GaussianDistribution(0.2, 3.0 / sqrt(10));
        $standardNormal = new GaussianDistribution(0, 1);

        $productDividedByStandardNormal = GaussianDistribution::divide($product, $standardNormal);
        $this->assertEqualsWithDelta(2.0, $productDividedByStandardNormal->getMean(), GaussianDistributionTest::ERROR_TOLERANCE);
        $this->assertEqualsWithDelta(3.0, $productDividedByStandardNormal->getStandardDeviation(), GaussianDistributionTest::ERROR_TOLERANCE);

        $product2              = new GaussianDistribution((4.0 * BasicMath::square(7) + 6.0 * BasicMath::square(5)) / (BasicMath::square(5) + BasicMath::square(7)), sqrt(((BasicMath::square(5) * BasicMath::square(7)) / (BasicMath::square(5) + BasicMath::square(7)))));
        $m4s5                  = new GaussianDistribution(4, 5);
        $product2DividedByM4S5 = GaussianDistribution::divide($product2, $m4s5);
        $this->assertEqualsWithDelta(6.0, $product2DividedByM4S5->getMean(), GaussianDistributionTest::ERROR_TOLERANCE);
        $this->assertEqualsWithDelta(7.0, $product2DividedByM4S5->getStandardDeviation(), GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testLogProductNormalization(): void
    {
        // Verified with Ralf Herbrich's F# implementation
        $standardNormal = new GaussianDistribution(0, 1);
        $lpn = GaussianDistribution::logProductNormalization($standardNormal, $standardNormal);
        $this->assertEqualsWithDelta(-1.2655121234846454, $lpn, GaussianDistributionTest::ERROR_TOLERANCE);

        $m1s2 = new GaussianDistribution(1, 2);
        $m3s4 = new GaussianDistribution(3, 4);
        $lpn2 = GaussianDistribution::logProductNormalization($m1s2, $m3s4);
        $this->assertEqualsWithDelta(-2.5168046699816684, $lpn2, GaussianDistributionTest::ERROR_TOLERANCE);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 0);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 0);
        $lrn  = GaussianDistribution::logProductNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 1);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 0);
        $lrn  = GaussianDistribution::logProductNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 0);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 1);
        $lrn  = GaussianDistribution::logProductNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);
    }

    public function testLogRatioNormalization(): void
    {
        // Verified with Ralf Herbrich's F# implementation
        $m1s2 = new GaussianDistribution(1, 2);
        $m3s4 = new GaussianDistribution(3, 4);
        $lrn  = GaussianDistribution::logRatioNormalization($m1s2, $m3s4);
        $this->assertEqualsWithDelta(2.6157405972171204, $lrn, GaussianDistributionTest::ERROR_TOLERANCE);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 0);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 0);
        $lrn  = GaussianDistribution::logRatioNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 1);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 0);
        $lrn  = GaussianDistribution::logRatioNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);

        $numerator = GaussianDistribution::fromPrecisionMean(1, 0);
        $denominator = GaussianDistribution::fromPrecisionMean(1, 1);
        $lrn  = GaussianDistribution::logRatioNormalization($numerator, $denominator);
        $this->assertEquals(0, $lrn);
    }

    public function testAbsoluteDifference(): void
    {
        // Verified with Ralf Herbrich's F# implementation
        $standardNormal = new GaussianDistribution(0, 1);
        $absDiff        = GaussianDistribution::absoluteDifference($standardNormal, $standardNormal);
        $this->assertEqualsWithDelta(0.0, $absDiff, GaussianDistributionTest::ERROR_TOLERANCE);

        $m1s2 = new GaussianDistribution(1, 2);
        $m3s4 = new GaussianDistribution(3, 4);
        $absDiff2 = GaussianDistribution::absoluteDifference($m1s2, $m3s4);
        $this->assertEqualsWithDelta(0.4330127018922193, $absDiff2, GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testSubtract(): void
    {
        // Verified with Ralf Herbrich's F# implementation
        $standardNormal = new GaussianDistribution(0, 1);
        $absDiff        = GaussianDistribution::subtract($standardNormal, $standardNormal);
        $this->assertEqualsWithDelta(0.0, $absDiff, GaussianDistributionTest::ERROR_TOLERANCE);

        $m1s2 = new GaussianDistribution(1, 2);
        $m3s4 = new GaussianDistribution(3, 4);
        $absDiff2 = GaussianDistribution::subtract($m1s2, $m3s4);
        $this->assertEqualsWithDelta(0.4330127018922193, $absDiff2, GaussianDistributionTest::ERROR_TOLERANCE);
    }

    public function testfromPrecisionMean(): void
    {
        $gd = GaussianDistribution::fromPrecisionMean(0, 0);
        $this->assertInfinite($gd->getVariance());
        $this->assertInfinite($gd->getStandardDeviation());
        $this->assertNan($gd->getMean());
        $this->assertEquals(0, $gd->getPrecisionMean());
        $this->assertEquals(0, $gd->getPrecision());
    }
}
