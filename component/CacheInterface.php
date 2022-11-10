<?php

declare(strict_types=1);

namespace Phant\Cache;

interface CacheInterface extends \Psr\SimpleCache\CacheInterface
{
    public function getOrSet(string $key, callable $getValue, \DateInterval|int|null $ttl = null): mixed;
}
