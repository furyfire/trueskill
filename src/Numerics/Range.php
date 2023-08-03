<?php

namespace DNW\Skills\Numerics;

// The whole purpose of this class is to make the code for the SkillCalculator(s)
// look a little cleaner

use Exception;

class Range
{
    final public function __construct(private int $min, private int $max)
    {
        if ($min > $max) {
            throw new Exception('min > max');
        }
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    protected static function create(int $min, int $max): static
    {
        return new static($min, $max);
    }

    // REVIEW: It's probably bad form to have access statics via a derived class, but the syntax looks better :-)

    public static function inclusive(int $min, int $max): static
    {
        return static::create($min, $max);
    }

    public static function exactly(int $value): static
    {
        return static::create($value, $value);
    }

    public static function atLeast(int $minimumValue): static
    {
        return static::create($minimumValue, PHP_INT_MAX);
    }

    public function isInRange(int $value): bool
    {
        return ($this->min <= $value) && ($value <= $this->max);
    }
}
