<?php

declare(strict_types=1);

namespace Phant\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class SimpleCache implements \Psr\SimpleCache\CacheInterface
{
    public const TTL_MINUTE	= 60;
    public const TTL_HOUR		= 3600;
    public const TTL_DAY		= 86400;
    public const TTL_MONTH		= 2628000;
    public const TTL_YEAR		= 31536000;

    protected readonly FilesystemAdapter $filesystemAdapter;

    public function __construct(
        string $cacheDirectory,
        string $cacheContainer,
        protected readonly \DateInterval|int|null $defaultTTL = self::TTL_DAY
    ) {
        $this->filesystemAdapter = new FilesystemAdapter($cacheContainer, 0, $cacheDirectory);
    }

    public function has(string $key): bool
    {
        return $this->filesystemAdapter->getItem($key)->isHit();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->filesystemAdapter->getItem($key)->get() ?? $default;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        try {
            $cacheItem = $this->filesystemAdapter->getItem($key);
            $cacheItem->set($value);
            $cacheItem->expiresAfter($ttl ?? $this->defaultTTL);
            $this->filesystemAdapter->save($cacheItem);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function delete(string $key): bool
    {
        try {
            $this->filesystemAdapter->delete($key);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function clear(): bool
    {
        try {
            $this->filesystemAdapter->clear();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $this->get($key, $default);
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
