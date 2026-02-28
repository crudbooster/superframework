<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Middleware\RateLimitMiddleware;
use App\Services\RedisService;
use Closure;

class RateLimitMiddlewareTest extends TestCase
{
    private RateLimitMiddleware $middleware;
    private $mockRedis;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockRedis = $this->createMock(RedisService::class);
        $this->middleware = new RateLimitMiddleware();
        $this->middleware->setRedis($this->mockRedis);
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }

    public function test_passes_when_redis_unavailable()
    {
        $this->mockRedis->method('getClient')->willReturn(null);
        $next = function() { return 'Passed'; };
        
        $result = $this->middleware->handle($next);
        $this->assertEquals('Passed', $result);
    }

    public function test_passes_when_under_limit()
    {
        $this->mockRedis->method('getClient')->willReturn($this->createMock(\Predis\Client::class));
        $this->mockRedis->expects($this->once())
            ->method('incr')
            ->willReturn(1);
        $this->mockRedis->expects($this->once())
            ->method('expire');
        
        $next = function() { return 'Passed'; };
        $result = $this->middleware->handle($next);
        
        $this->assertEquals('Passed', $result);
    }

    public function test_fails_when_over_limit()
    {
        $this->mockRedis->method('getClient')->willReturn($this->createMock(\Predis\Client::class));
        $this->mockRedis->expects($this->once())
            ->method('incr')
            ->willReturn(61);
            
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Too many requests');
        
        $next = function() { return 'Passed'; };
        $this->middleware->handle($next);
    }
}
