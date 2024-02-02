<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Rating;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    public function testGetRatingParameters(): void
    {
        $rating = new Rating(100, 10, 5);
        $this->assertEquals(100, $rating->getMean());
        $this->assertEquals(10, $rating->getStandardDeviation());
        $this->assertEquals(50, $rating->getConservativeRating());
        $this->assertEquals("mean=100.0000, standardDeviation=10.0000", (string)$rating);
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
        $this->assertEquals("mean=150.0000, standardDeviation=10.0000", (string)$rating_partial);
    }
}
