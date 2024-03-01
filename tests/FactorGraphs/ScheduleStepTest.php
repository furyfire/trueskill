<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\FactorGraphs;

use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\FactorGraphs\Factor;
use PHPUnit\Framework\TestCase;

class ScheduleStepTest extends TestCase
{
    public function testtoStringInterface(): void
    {
        $stub = $this->createStub(Factor::class);
        $ss = new ScheduleStep('dummy', $stub, 0);
        $this->assertEquals('dummy', (string)$ss);
    }
}
