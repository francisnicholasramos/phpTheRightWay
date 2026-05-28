<?php

namespace Core;

class Cache {
    private static ?\Redis $instance = null;

    public static function getInstance(): \Redis {
        if (self::$instance === null) {
            $redis = new \Redis();
            $url = $_ENV['REDIS_URL'] ?? null;
            if ($url) {
                $parts = parse_url($url);
                $redis->connect($parts['host'], $parts['port'] ?? 6379);
                if (!empty($parts['pass'])) {
                    $redis->auth($parts['pass']);
                }
            } else {
                $redis->connect('127.0.0.1', 6379);
            }
            self::$instance = $redis;
        }
        return self::$instance;
    }

    public static function remember(string $key, int $ttl, callable $callback): mixed {
        $redis = self::getInstance();
        $cached = $redis->get($key);

        if ($cached !== false) {
            return unserialize($cached);
        }

        $value = $callback();
        $redis->setex($key, $ttl, serialize($value));
        return $value;
    }

    public static function forget(string $key): void {
        self::getInstance()->del($key);
    }
}
