<?php

declare(strict_types=1);

namespace DNW\Skills;

/**
 * Basic hashmap that supports object keys.
 */
final class HashMap
{
    /**
     * @var mixed[] $hashToValue
     */
    private array $hashToValue = [];

    /**
     * @var mixed[] $hashToKey
     */
    private array $hashToKey = [];

    public function getValue(object $key): mixed
    {
        $hash = spl_object_id($key);

        return $this->hashToValue[$hash];
    }

    public function setValue(object $key, mixed $value): self
    {
        $hash = spl_object_id($key);
        $this->hashToKey[$hash] = $key;
        $this->hashToValue[$hash] = $value;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getAllKeys(): array
    {
        return array_values($this->hashToKey);
    }

    /**
     * @return mixed[]
     */
    public function getAllValues(): array
    {
        return array_values($this->hashToValue);
    }

    public function count(): int
    {
        return count($this->hashToKey);
    }
}
