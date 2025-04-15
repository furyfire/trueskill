<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Team;
use DNW\Skills\RatingContainer;
use DNW\Skills\HashMap;
use DNW\Skills\Player;
use DNW\Skills\Rating;
use DNW\Skills\Guard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Team::class)]
#[CoversClass(RatingContainer::class)]
#[UsesClass(Hashmap::class)]
#[UsesClass(Player::class)]
#[UsesClass(Rating::class)]
#[UsesClass(Guard::class)]
final class TeamTest extends TestCase
{
    public function testTeam(): void
    {
        $p1 = new Player(1);
        $p2 = new Player(2);

        $r1 = new Rating(100, 10);
        $r2 = new Rating(200, 20);

        $rc = new Team($p1, $r1);

        $this->assertEquals($r1, $rc->getRating($p1));
        $this->assertEquals([$p1], $rc->getAllPlayers());
        $this->assertEquals([$r1], $rc->getAllRatings());
        $this->assertEquals(1, $rc->count());

        $rc->addPlayer($p2, $r2);

        $this->assertEquals($r2, $rc->getRating($p2));

        $this->assertEquals([$p1, $p2], $rc->getAllPlayers());
        $this->assertEquals([$r1, $r2], $rc->getAllRatings());
        $this->assertEquals(2, $rc->count());
    }

    public function testTeamConstructor(): void
    {
        $p = new Player(0);
        $r = new Rating(100, 10);

        $rc = new Team(NULL, NULL);
        $this->assertEquals(0, $rc->count());

        $rc = new Team($p, NULL);
        $this->assertEquals(0, $rc->count());

        $rc = new Team(NULL, $r);
        $this->assertEquals(0, $rc->count());

        $rc = new Team($p, $r);
        $this->assertEquals($r, $rc->getRating($p));
        $this->assertEquals([$p], $rc->getAllPlayers());
        $this->assertEquals([$r], $rc->getAllRatings());
        $this->assertEquals(1, $rc->count());
    }
}
