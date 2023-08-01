<?php

namespace DNW\Skills\Numerics;

// The whole purpose of this class is to make the code for the SkillCalculator(s)
// look a little cleaner

use Exception;

class Range
{
    private int $_min;

    private int $_max;

    public function __construct(int $min, int $max)
    {
        if ($min > $max) {
            throw new Exception('min > max');
        }

        $this->_min = $min;
        $this->_max = $max;
    }

    public function getMin(): int
    {
        return $this->_min;
    }

    public function getMax(): int
    {
        return $this->_max;
    }

    protected static function create(int $min, int $max): self
    {
        return new Range($min, $max);
    }

    // REVIEW: It's probably bad form to have access statics via a derived class, but the syntax looks better :-)

    public static function inclusive(int $min, int $max): self
    {
        return static::create($min, $max);
    }

    public static function exactly(int $value): self
    {
        return static::create($value, $value);
    }

    public static function atLeast(int $minimumValue): self
    {
        return static::create($minimumValue, PHP_INT_MAX);
    }

    public function isInRange(int $value): bool
    {
        return ($this->_min <= $value) && ($value <= $this->_max);
    }
}
