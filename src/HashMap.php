<?php

namespace DNW\Skills;

/**
 * Basic hashmap that supports object keys.
 */
class HashMap
{
    /**
     * @var mixed[] $hashToValue
     */
    private array $hashToValue = [];

    /**
     * @var mixed[] $hashToKey
     */
    private array $hashToKey = [];

    public function getValue(string|object $key): mixed
    {
        $hash = self::getHash($key);

        return $this->hashToValue[$hash];
    }

    public function setValue(string|object $key, mixed $value): self
    {
        $hash = self::getHash($key);
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

    private static function getHash(string|object $key): string
    {
        if (is_object($key)) {
            return spl_object_hash($key);
        }

        return $key;
    }
}
