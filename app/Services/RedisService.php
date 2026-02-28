<?php

namespace App\Services;

use Predis\Client;

class RedisService
{
    private ?Client $client = null;

    public function __construct()
    {
        $config = include base_path("configs/App.php");
        if (isset($config['redis'])) {
            $this->client = new Client($config['redis']);
        }
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function get(string $key): mixed
    {
        return $this->client ? unserialize($this->client->get($key)) : null;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        if ($this->client) {
            $this->client->setex($key, $ttl, serialize($value));
        }
    }

    public function incr(string $key): int
    {
        return $this->client ? $this->client->incr($key) : 0;
    }

    public function expire(string $key, int $ttl): void
    {
        if ($this->client) {
            $this->client->expire($key, $ttl);
        }
    }
}
