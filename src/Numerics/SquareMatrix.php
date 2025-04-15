<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

final class SquareMatrix extends Matrix
{
    public function __construct(float|int ...$allValues)
    {
        $size = (int)sqrt(count($allValues));

        $matrixData = [];
        $allValuesIndex = 0;

        for ($currentRow = 0; $currentRow < $size; ++$currentRow) {
            for ($currentColumn = 0; $currentColumn < $size; ++$currentColumn) {
                $matrixData[$currentRow][$currentColumn] = $allValues[$allValuesIndex++];
            }
        }

        parent::__construct($size, $size, $matrixData);
    }
}
