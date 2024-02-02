<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

class Vector extends Matrix
{
    /**
     * @param float[] $vectorValues
     */
    public function __construct(array $vectorValues)
    {
        $columnValues = [];
        foreach ($vectorValues as $currentVectorValue) {
            $columnValues[] = [$currentVectorValue];
        }
        parent::__construct(count($vectorValues), 1, $columnValues);
    }
}
