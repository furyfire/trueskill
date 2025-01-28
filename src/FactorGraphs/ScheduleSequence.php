<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class ScheduleSequence extends Schedule
{
    /**
     * @param Schedule[] $schedules
     */
    public function __construct(private readonly array $schedules)
    {
    }

    #[\Override]
    public function visit(int $depth = -1, int $maxDepth = 0): float
    {
        $maxDelta = 0;

        $schedules = $this->schedules;
        foreach ($schedules as $currentSchedule) {
            $currentVisit = $currentSchedule->visit($depth + 1, $maxDepth);
            $maxDelta = max($currentVisit, $maxDelta);
        }

        return $maxDelta;
    }
}
