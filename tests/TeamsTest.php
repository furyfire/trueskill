<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Teams;
use DNW\Skills\Team;
use DNW\Skills\RatingContainer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Teams::class)]
#[UsesClass(Team::class)]
#[UsesClass(RatingContainer::class)]
class TeamsTest extends TestCase
{
    public function testTeamsConcat(): void
    {
        $t1 = new Team();
        $t2 = new Team();
        $t3 = new Team();

        $this->assertEquals([$t1], Teams::concat($t1));
        $this->assertEquals([$t1, $t2, $t3], Teams::concat($t1, $t2, $t3));
    }
}
