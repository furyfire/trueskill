<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

class IdentityMatrix extends DiagonalMatrix
{
    public function __construct(int $rows)
    {
        parent::__construct(array_fill(0, $rows, 1));
    }
}
