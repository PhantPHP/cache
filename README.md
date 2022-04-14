# Cache

## Requirments

PHP >= 8.0


## Install

`composer require phant/cache`

## Usages

### Init cache

```php
use Phant\Cache\SimpleCache;

$cache = new SimpleCache('path/cache/', 'my-cache-container', 10 * SimpleCache::TTL_MINUTE);
```

### Set cache

```php
$cache->set('my-key', 'my-val');
```

### Get cache

```php
$cache->get('my-key');
```

### Test cache

```php
$cache->has('my-key');
```

### Caching process

```php
$cacheKey = 'my-key';

if ($cache->has($cacheKey)) {
	var = $cache->get($cacheKey);
} else {
	$var = 'my-val';
	$cache->set($cacheKey, $var, SimpleCache::TTL_HOUR);
}
```
