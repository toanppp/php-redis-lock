<?php

namespace RedisLock;

use Redis;

class RedisLock
{
    const SLEEP_TIME = 1;

    private static RedisLock $instance;

    private Redis $redisClient;

    private array $keys;

    public function __construct(string $host, int $port)
    {
        $this->redisClient = new Redis();

        $this->redisClient->connect($host, $port);

        $this->keys = [];
    }

    public static function getInstance(string $host, int $port): RedisLock
    {
        if (!isset(self::$instance)) {
            self::$instance = new RedisLock($host, $port);
        }

        return self::$instance;
    }

    public function acquire(string $key, string $type = null)
    {
        while (!$this->redisClient->setnx($key, true)) {
            sleep(static::SLEEP_TIME);
        }

        if (isset($type)) {
            $this->keys[$type] = $key;
            return;
        }

        $this->keys[] = $key;
    }

    public function release(string $key)
    {
        $index = array_search($key, $this->keys);

        if ($index === false) {
            return;
        }

        $this->delete($index);
    }

    public function releaseByType(string $type)
    {
        if (!isset($this->keys[$type])) {
            return;
        }

        $this->delete($type);
    }

    private function delete(string $index)
    {
        if ($this->redisClient->del($this->keys[$index])) {
            unset($this->keys[$index]);
        }
    }
}
