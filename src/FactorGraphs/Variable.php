<?php

namespace DNW\Skills\FactorGraphs;

class Variable implements \Stringable
{
    private string $name;

    private mixed $value;

    public function __construct(string $name, private mixed $prior)
    {
        $this->name = 'Variable[' . $name . ']';
        $this->resetToPrior();
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function resetToPrior(): void
    {
        $this->value = $this->prior;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
