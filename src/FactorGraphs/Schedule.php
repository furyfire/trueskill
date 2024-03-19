<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

abstract class Schedule
{
    abstract public function visit(int $depth = -1, int $maxDepth = 0): float;
}
