<?php

namespace DNW\Skills\FactorGraphs;

class ScheduleSequence extends Schedule
{
    public function __construct($name, private readonly array $schedules)
    {
        parent::__construct($name);
    }

    public function visit($depth = -1, $maxDepth = 0)
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
