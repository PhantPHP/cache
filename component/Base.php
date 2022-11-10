<?php

declare(strict_types=1);

namespace Phant\Cache;

abstract class Base implements \Phant\Cache\CacheInterface
{
    public const TTL_MINUTE  = 60;
    public const TTL_HOUR    = 3600;
    public const TTL_DAY     = 86400;
    public const TTL_WEEK    = 604800;
    public const TTL_MONTH   = 2628000;
    public const TTL_QUARTER = 7884000;
    public const TTL_YEAR    = 31536000;

    public function getOrSet(string $key, callable $getValue, \DateInterval|int|null $ttl = null): mixed
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $value = call_user_func($getValue);

        $this->set($key, $value, $ttl);

        return $value;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $this->get($key);
        }
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        try {
            foreach ($values as $key => $value) {
                if (!$this->set($key, $value, $ttl)) {
                    throw new \Exception();
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        try {
            foreach ($keys as $key) {
                if (!$this->delete($key)) {
                    throw new \Exception();
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
