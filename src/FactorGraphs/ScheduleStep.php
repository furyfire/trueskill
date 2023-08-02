<?php

namespace DNW\Skills\FactorGraphs;

class ScheduleStep extends Schedule
{
    public function __construct(string $name, private readonly Factor $factor, private int $index)
    {
        parent::__construct($name);
    }

    public function visit(int $depth = -1, int $maxDepth = 0): float
    {
        $currentFactor = $this->factor;

        return $currentFactor->updateMessageIndex($this->index);
    }
}
