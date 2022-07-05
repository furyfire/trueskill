<?php

namespace DNW\Skills\Numerics;

class Vector extends Matrix
{
    public function __construct(array $vectorValues)
    {
        $columnValues = [];
        foreach ($vectorValues as $currentVectorValue) {
            $columnValues[] = [$currentVectorValue];
        }
        parent::__construct(count($vectorValues), 1, $columnValues);
    }
}
