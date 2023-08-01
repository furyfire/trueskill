<?php

namespace DNW\Skills;

/**
 * Basic hashmap that supports object keys.
 */
class HashMap
{
    private array $hashToValue = [];

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

    public function getAllKeys(): array
    {
        return array_values($this->hashToKey);
    }

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
