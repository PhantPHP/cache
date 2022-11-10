# Cache

## Requirments

PHP >= 8.1


## Install

`composer require phant/cache`


## Usages

Phant Cache implement the PSR-16 SimpleCache interface :
`Psr\SimpleCache\CacheInterface`


### Init cache

#### File

```php
use Phant\Cache\File as CacheFile;

$cache = new CacheFile('path/cache/', 'my-cache-container', CacheFile::TTL_HOUR);
```


### Get or set 

```php
$val = $cache->getOrSet(
	'my-key',
	function () {
		return 'my-val';
	},
	$cache::TTL_HOUR
);
```


### Has (PSR-16 SimpleCache)

```php
if ($cache->has('my-key')) {
	
}
```


### Set (PSR-16 SimpleCache)

```php
$cache->set('my-key', 'my-val', $cache::TTL_HOUR);
```


### Get (PSR-16 SimpleCache)

```php
$val = $cache->get('my-key');
```


### Delete (PSR-16 SimpleCache)

```php
$cache->delete('my-key');
```


### Set multiple (PSR-16 SimpleCache)

```php
$cache->setMultiple([
	'my-key-1' => 'val-1',
	'my-key-2' => 'val-2',
	'my-key-3' => 'val-3',
], $cache::TTL_HOUR);
```


### Get multiple (PSR-16 SimpleCache)

```php
foreach ($cache->getMultiple([
	'my-key-1',
	'my-key-2',
	'my-key-3',
]) as $val) {

}
```


### Delete multiple (PSR-16 SimpleCache)

```php
$cache->deleteMultiple([
	'my-key-1',
	'my-key-2',
	'my-key-3',
]);
```


### Clear (PSR-16 SimpleCache)

```php
$cache->clear();
```
