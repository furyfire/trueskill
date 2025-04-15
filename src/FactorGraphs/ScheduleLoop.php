<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

final class ScheduleLoop extends Schedule
{
    public function __construct(private readonly Schedule $scheduleToLoop, private readonly float $maxDelta)
    {
    }

    #[\Override]
    public function visit(int $depth = -1, int $maxDepth = 0): float
    {
        $delta = $this->scheduleToLoop->visit($depth + 1, $maxDepth);
        while ($delta > $this->maxDelta) {
            $delta = $this->scheduleToLoop->visit($depth + 1, $maxDepth);
        }

        return $delta;
    }
}
