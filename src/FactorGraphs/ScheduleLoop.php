<?php

namespace DNW\Skills\FactorGraphs;

class ScheduleLoop extends Schedule
{
    public function __construct(string $name, private readonly Schedule $scheduleToLoop, private float $maxDelta)
    {
        parent::__construct($name);
    }

    public function visit(int $depth = -1, int $maxDepth = 0): float
    {
        $delta = $this->scheduleToLoop->visit($depth + 1, $maxDepth);
        while ($delta > $this->maxDelta) {
            $delta = $this->scheduleToLoop->visit($depth + 1, $maxDepth);
        }

        return $delta;
    }
}
