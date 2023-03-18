# PHP Redis Lock

## About
Access synchronization mechanism.

## Installation

```shell
composer require toanppp/php-redis-lock
```

## Methods

### RedisLock :: getInstance ( `string` host, `int` port )
Create or get singleton instance of RedisLock.

```php
$redisLockInstance = RedisLock::getInstance('127.0.0.1', 6379);
```

### RedisLock :: acquire ( `string` key, `string` | `null` type = `null` )
Lock a key, with or without type.

```php
$redisLockInstance->acquire('key');
```

### RedisLock :: release ( `string` key )
Release a key.

```php
$redisLockInstance->release('key');
```

### RedisLock :: releaseByType ( `string` type )
Release a key by type.

```php
$type = 'uniqueKey';
$redisLockInstance->acquire(uniqid(), $type);

$redisLockInstance->releaseByType($type);
```
