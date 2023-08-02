<?php

namespace DNW\Skills\FactorGraphs;

class Message implements \Stringable
{
    public function __construct(private ?object $value = null, private ?string $name = null)
    {

    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
