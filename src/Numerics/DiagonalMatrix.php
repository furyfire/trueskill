<?php

declare(strict_types=1);

namespace DNW\Skills\Numerics;

class DiagonalMatrix extends Matrix
{
    /**
     * @param float[] $diagonalValues
     */
    public function __construct(array $diagonalValues)
    {
        $diagonalCount = count($diagonalValues);

        parent::__construct($diagonalCount, $diagonalCount);

        for ($currentRow = 0; $currentRow < $diagonalCount; ++$currentRow) {
            for ($currentCol = 0; $currentCol < $diagonalCount; ++$currentCol) {
                if ($currentRow === $currentCol) {
                    $this->setValue($currentRow, $currentCol, $diagonalValues[$currentRow]);
                } else {
                    $this->setValue($currentRow, $currentCol, 0);
                }
            }
        }
    }
}
