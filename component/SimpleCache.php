<?php
declare(strict_types=1);

namespace Phant\Cache;
use Psr\SimpleCache\CacheInterface;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class SimpleCache implements CacheInterface
{
	const TTL_MINUTE	= 60;
	const TTL_HOUR		= 3600;
	const TTL_DAY		= 86400;
	const TTL_MONTH		= 2628000;
	const TTL_YEAR		= 31536000;
	
	protected FilesystemAdapter $filesystemAdapter;
	protected \DateInterval|int|null $defaultTTL;

	public function __construct(string $cacheDirectory, string $cacheContainer, int $defaultTTL = self::TTL_DAY)
	{
		$this->filesystemAdapter = new FilesystemAdapter($cacheContainer, 0, $cacheDirectory);
		$this->defaultTTL = $defaultTTL;
	}
	
	public function has(string $key): bool
	{
		return $this->filesystemAdapter->getItem($key)->isHit();
	}

	public function get(string $key, mixed $default = null): mixed
	{
		return $this->filesystemAdapter->getItem($key)->get();
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
			yield $this->get($key);
		}
	}
	
	public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
	{
		try {
			foreach ($values as $key => $value) {
				if (!$this->set($key, $value, $ttl)) {
					throw new \Exception;
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
					throw new \Exception;
				}
			}
		} catch (\Exception $e) {
			return false;
		}
		
		return true;
	}
}
