<?php

namespace App\Middleware;

use App\Services\RedisService;
use Closure;
use SuperFrameworkEngine\Foundation\Container;

class RateLimitMiddleware implements \SuperFrameworkEngine\Interfaces\Middleware
{
    private RedisService $redis;
    private int $limit = 60; // requests
    private int $decay = 60; // seconds

    public function __construct()
    {
        $this->redis = Container::getInstance()->make(RedisService::class);
    }

    public function handle(Closure $next)
    {
        if (!$this->redis->getClient()) {
            return $next();
        }

        $key = 'rate_limit:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $current = $this->redis->incr($key);

        if ($current === 1) {
            $this->redis->expire($key, $this->decay);
        }

        if ($current > $this->limit) {
            header('HTTP/1.1 429 Too Many Requests');
            header('Retry-After: ' . $this->decay);
            throw new \Exception("Too many requests. Please try again later.", 429);
        }

        header('X-RateLimit-Limit: ' . $this->limit);
        header('X-RateLimit-Remaining: ' . ($this->limit - $current));

        return $next();
    }
}
