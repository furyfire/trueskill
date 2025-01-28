<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class ScheduleStep extends Schedule
{
    public function __construct(private readonly Factor $factor, private readonly int $index)
    {
    }

    #[\Override]
    public function visit(int $depth = -1, int $maxDepth = 0): float
    {
        return $this->factor->updateMessageIndex($this->index);
    }
}
