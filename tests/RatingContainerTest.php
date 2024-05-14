<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\RatingContainer;
use DNW\Skills\HashMap;
use DNW\Skills\Player;
use DNW\Skills\Rating;
use DNW\Skills\Guard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(RatingContainer::class)]
#[UsesClass(Hashmap::class)]
#[UsesClass(Player::class)]
#[UsesClass(Rating::class)]
#[UsesClass(Guard::class)]
class RatingContainerTest extends TestCase
{
    public function testRatingContainer(): void
    {
        $rc = new RatingContainer();

        $this->assertEquals([], $rc->getAllPlayers());
        $this->assertEquals([], $rc->getAllRatings());
        $this->assertEquals(0, $rc->count());

        $p1 = new Player(1);
        $p2 = new Player(2);

        $r1 = new Rating(100, 10);
        $r2 = new Rating(200, 20);

        $rc->setRating($p1, $r1);
        $rc->setRating($p2, $r2);

        $this->assertEquals($r1, $rc->getRating($p1));
        $this->assertEquals($r2, $rc->getRating($p2));

        $this->assertEquals([$p1, $p2], $rc->getAllPlayers());
        $this->assertEquals([$r1, $r2], $rc->getAllRatings());
        $this->assertEquals(2, $rc->count());
    }
}
