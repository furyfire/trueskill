<?php

namespace DNW\Skills\FactorGraphs;

class ScheduleLoop extends Schedule
{
    public function __construct($name, private readonly Schedule $_scheduleToLoop, private $_maxDelta)
    {
        parent::__construct($name);
    }

    public function visit(int $depth = -1, int $maxDepth = 0)
    {
        $totalIterations = 1;
        $delta = $this->_scheduleToLoop->visit($depth + 1, $maxDepth);
        while ($delta > $this->_maxDelta) {
            $delta = $this->_scheduleToLoop->visit($depth + 1, $maxDepth);
            $totalIterations++;
        }

        return $delta;
    }
}
