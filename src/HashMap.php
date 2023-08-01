<?php

namespace DNW\Skills;

/**
 * Basic hashmap that supports object keys.
 */
class HashMap
{
    private array $_hashToValue = [];

    private array $_hashToKey = [];

    public function getValue(string|object $key): mixed
    {
        $hash = self::getHash($key);

        return $this->_hashToValue[$hash];
    }

    public function setValue(string|object $key, mixed $value): self
    {
        $hash = self::getHash($key);
        $this->_hashToKey[$hash] = $key;
        $this->_hashToValue[$hash] = $value;

        return $this;
    }

    public function getAllKeys(): array
    {
        return array_values($this->_hashToKey);
    }

    public function getAllValues(): array
    {
        return array_values($this->_hashToValue);
    }

    public function count(): int
    {
        return count($this->_hashToKey);
    }

    private static function getHash(string|Object $key): string
    {
        if (is_object($key)) {
            return spl_object_hash($key);
        }

        return $key;
    }
}
