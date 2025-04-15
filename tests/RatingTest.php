<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Rating;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Numerics\GaussianDistribution;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Rating::class)]
#[UsesClass(BasicMath::class)]
#[UsesClass(GaussianDistribution::class)]
final class RatingTest extends TestCase
{
    public function testGetRatingParameters(): void
    {
        $rating = new Rating(100, 10, 5);
        $this->assertEquals(100, $rating->getMean());
        $this->assertEquals(10, $rating->getStandardDeviation());
        $this->assertEquals(50, $rating->getConservativeRating());
    }

    public function testPartialUpdate(): void
    {
        $rating    = new Rating(100, 10, 5);
        $ratingOld = new Rating(100, 10, 5);
        $ratingNew = new Rating(200, 10, 5);

        $rating_partial = $rating->getPartialUpdate($ratingOld, $ratingNew, 0.5);

        $this->assertEquals(150, $rating_partial->getMean());
        $this->assertEquals(10, $rating_partial->getStandardDeviation());
        $this->assertEquals(100, $rating_partial->getConservativeRating());
    }
}
