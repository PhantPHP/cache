<?php

declare(strict_types=1);

namespace Phant\Cache;

final class Runtime extends \Phant\Cache\Base
{
    private array $store;

    public function __construct()
    {
        $this->store = [];
    }

    public function has(string $key): bool
    {
        return isset($this->store[$key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->store[$key] ?? $default;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $this->store[$key] = $value;

        return true;
    }

    public function delete(string $key): bool
    {
        if (!isset($this->store[$key])) {
            return false;
        }

        unset($this->store[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->store = [];

        return true;
    }
}
