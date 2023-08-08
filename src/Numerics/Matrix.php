<?php

namespace DNW\Skills\Numerics;

use Exception;

class Matrix
{
    public const ERROR_TOLERANCE = 0.0000000001;

    /**
     * @param array<int,array<int,float>> $matrixRowData
     */
    public function __construct(private int $rowCount = 0, private int $columnCount = 0, private array $matrixRowData = array())
    {
    }

    /**
     * @param array<int,array<int,float>> $columnValues
     */
    public static function fromColumnValues(int $rows, int $columns, array $columnValues): self
    {
        $data = [];
        $result = new Matrix($rows, $columns, $data);

        for ($currentColumn = 0; $currentColumn < $columns; $currentColumn++) {
            $currentColumnData = $columnValues[$currentColumn];

            for ($currentRow = 0; $currentRow < $rows; $currentRow++) {
                $result->setValue($currentRow, $currentColumn, $currentColumnData[$currentRow]);
            }
        }

        return $result;
    }

    public static function fromRowsColumns(int $rows, int $cols, float|int ...$args): Matrix
    {
        $result = new Matrix($rows, $cols);
        $currentIndex = 0;

        for ($currentRow = 0; $currentRow < $rows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $cols; $currentCol++) {
                $result->setValue($currentRow, $currentCol, $args[$currentIndex++]);
            }
        }

        return $result;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getColumnCount(): int
    {
        return $this->columnCount;
    }

    public function getValue(int $row, int $col): float|int
    {
        return $this->matrixRowData[$row][$col];
    }

    public function setValue(int $row, int $col, float|int $value): void
    {
        $this->matrixRowData[$row][$col] = $value;
    }

    public function getTranspose(): self
    {
        // Just flip everything
        $transposeMatrix = [];

        $rowMatrixData = $this->matrixRowData;
        for (
            $currentRowTransposeMatrix = 0;
             $currentRowTransposeMatrix < $this->columnCount;
             $currentRowTransposeMatrix++
        ) {
            for (
                $currentColumnTransposeMatrix = 0;
                 $currentColumnTransposeMatrix < $this->rowCount;
                 $currentColumnTransposeMatrix++
            ) {
                $transposeMatrix[$currentRowTransposeMatrix][$currentColumnTransposeMatrix] =
                    $rowMatrixData[$currentColumnTransposeMatrix][$currentRowTransposeMatrix];
            }
        }

        return new Matrix($this->columnCount, $this->rowCount, $transposeMatrix);
    }

    private function isSquare(): bool
    {
        return ($this->rowCount == $this->columnCount) && ($this->rowCount > 0);
    }

    public function getDeterminant(): float
    {
        // Basic argument checking
        if (! $this->isSquare()) {
            throw new Exception('Matrix must be square!');
        }

        if ($this->rowCount == 1) {
            // Really happy path :)
            return $this->matrixRowData[0][0];
        }

        if ($this->rowCount == 2) {
            // Happy path!
            // Given:
            // | a b |
            // | c d |
            // The determinant is ad - bc
            $a = $this->matrixRowData[0][0];
            $b = $this->matrixRowData[0][1];
            $c = $this->matrixRowData[1][0];
            $d = $this->matrixRowData[1][1];

            return $a * $d - $b * $c;
        }

        // I use the Laplace expansion here since it's straightforward to implement.
        // It's O(n^2) and my implementation is especially poor performing, but the
        // core idea is there. Perhaps I should replace it with a better algorithm
        // later.
        // See http://en.wikipedia.org/wiki/Laplace_expansion for details

        $result = 0.0;

        // I expand along the first row
        for ($currentColumn = 0; $currentColumn < $this->columnCount; $currentColumn++) {
            $firstRowColValue = $this->matrixRowData[0][$currentColumn];
            $cofactor = $this->getCofactor(0, $currentColumn);
            $itemToAdd = $firstRowColValue * $cofactor;
            $result += $itemToAdd;
        }

        return $result;
    }

    public function getAdjugate(): SquareMatrix|self
    {
        if (! $this->isSquare()) {
            throw new Exception('Matrix must be square!');
        }

        // See http://en.wikipedia.org/wiki/Adjugate_matrix
        if ($this->rowCount == 2) {
            // Happy path!
            // Adjugate of:
            // | a b |
            // | c d |
            // is
            // | d -b |
            // | -c a |

            $a = $this->matrixRowData[0][0];
            $b = $this->matrixRowData[0][1];
            $c = $this->matrixRowData[1][0];
            $d = $this->matrixRowData[1][1];

            return new SquareMatrix(
                $d,
                -$b,
                -$c,
                $a
            );
        }

        // The idea is that it's the transpose of the cofactors
        $result = [];

        for ($currentColumn = 0; $currentColumn < $this->columnCount; $currentColumn++) {
            for ($currentRow = 0; $currentRow < $this->rowCount; $currentRow++) {
                $result[$currentColumn][$currentRow] = $this->getCofactor($currentRow, $currentColumn);
            }
        }

        return new Matrix($this->columnCount, $this->rowCount, $result);
    }

    public function getInverse(): Matrix|SquareMatrix
    {
        if (($this->rowCount == 1) && ($this->columnCount == 1)) {
            return new SquareMatrix(1.0 / $this->matrixRowData[0][0]);
        }

        // Take the simple approach:
        // http://en.wikipedia.org/wiki/Cramer%27s_rule#Finding_inverse_matrix
        $determinantInverse = 1.0 / $this->getDeterminant();
        $adjugate = $this->getAdjugate();

        return self::scalarMultiply($determinantInverse, $adjugate);
    }

    public static function scalarMultiply(float|int $scalarValue, Matrix $matrix): Matrix
    {
        $rows = $matrix->getRowCount();
        $columns = $matrix->getColumnCount();
        $newValues = [];

        for ($currentRow = 0; $currentRow < $rows; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $columns; $currentColumn++) {
                $newValues[$currentRow][$currentColumn] = $scalarValue * $matrix->getValue($currentRow, $currentColumn);
            }
        }

        return new Matrix($rows, $columns, $newValues);
    }

    public static function add(Matrix $left, Matrix $right): Matrix
    {
        if (
            ($left->getRowCount() != $right->getRowCount())

            || ($left->getColumnCount() != $right->getColumnCount())
        ) {
            throw new Exception('Matrices must be of the same size');
        }

        // simple addition of each item

        $resultMatrix = [];

        for ($currentRow = 0; $currentRow < $left->getRowCount(); $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $right->getColumnCount(); $currentColumn++) {
                $resultMatrix[$currentRow][$currentColumn] =
                    $left->getValue($currentRow, $currentColumn)
                    +
                    $right->getValue($currentRow, $currentColumn);
            }
        }

        return new Matrix($left->getRowCount(), $right->getColumnCount(), $resultMatrix);
    }

    public static function multiply(Matrix $left, Matrix $right): Matrix
    {
        // Just your standard matrix multiplication.
        // See http://en.wikipedia.org/wiki/Matrix_multiplication for details

        if ($left->getColumnCount() != $right->getRowCount()) {
            throw new Exception('The width of the left matrix must match the height of the right matrix');
        }

        $resultRows = $left->getRowCount();
        $resultColumns = $right->getColumnCount();

        $resultMatrix = [];

        for ($currentRow = 0; $currentRow < $resultRows; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $resultColumns; $currentColumn++) {
                $productValue = 0;

                for ($vectorIndex = 0; $vectorIndex < $left->getColumnCount(); $vectorIndex++) {
                    $leftValue = $left->getValue($currentRow, $vectorIndex);
                    $rightValue = $right->getValue($vectorIndex, $currentColumn);
                    $vectorIndexProduct = $leftValue * $rightValue;
                    $productValue += $vectorIndexProduct;
                }

                $resultMatrix[$currentRow][$currentColumn] = $productValue;
            }
        }

        return new Matrix($resultRows, $resultColumns, $resultMatrix);
    }

    private function getMinorMatrix(int $rowToRemove, int $columnToRemove): Matrix
    {
        // See http://en.wikipedia.org/wiki/Minor_(linear_algebra)

        // I'm going to use a horribly naïve algorithm... because I can :)
        $result = [];

        $actualRow = 0;

        for ($currentRow = 0; $currentRow < $this->rowCount; $currentRow++) {
            if ($currentRow == $rowToRemove) {
                continue;
            }

            $actualCol = 0;

            for ($currentColumn = 0; $currentColumn < $this->columnCount; $currentColumn++) {
                if ($currentColumn == $columnToRemove) {
                    continue;
                }

                $result[$actualRow][$actualCol] = $this->matrixRowData[$currentRow][$currentColumn];

                $actualCol++;
            }

            $actualRow++;
        }

        return new Matrix($this->rowCount - 1, $this->columnCount - 1, $result);
    }

    public function getCofactor(int $rowToRemove, int $columnToRemove): float
    {
        // See http://en.wikipedia.org/wiki/Cofactor_(linear_algebra) for details
        // REVIEW: should things be reversed since I'm 0 indexed?
        $sum = $rowToRemove + $columnToRemove;
        $isEven = ($sum % 2 == 0);

        if ($isEven) {
            return $this->getMinorMatrix($rowToRemove, $columnToRemove)->getDeterminant();
        } else {
            return -1.0 * $this->getMinorMatrix($rowToRemove, $columnToRemove)->getDeterminant();
        }
    }

    public function equals(Matrix $otherMatrix): bool
    {
        if (($this->rowCount != $otherMatrix->getRowCount()) || ($this->columnCount != $otherMatrix->getColumnCount())) {
            return false;
        }

        for ($currentRow = 0; $currentRow < $this->rowCount; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $this->columnCount; $currentColumn++) {
                $delta =
                    abs(
                        $this->matrixRowData[$currentRow][$currentColumn] -
                        $otherMatrix->getValue($currentRow, $currentColumn)
                    );

                if ($delta > self::ERROR_TOLERANCE) {
                    return false;
                }
            }
        }

        return true;
    }
}
