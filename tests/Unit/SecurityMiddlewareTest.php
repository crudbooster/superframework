<?php

namespace Tests\Unit;

use App\Middleware\SecurityMiddleware;
use Tests\TestCase;

class SecurityMiddlewareTest extends TestCase
{
    public function test_handle_sets_security_headers()
    {
        $this->markTestSkipped('Skipping header test in CLI environment');
        $middleware = new SecurityMiddleware();
        $next = function() { return 'Response'; };

        $result = $middleware->handle($next);

        $this->assertEquals('Response', $result);
        
        $headers = headers_list();
        $this->assertContains('X-XSS-Protection: 1; mode=block', $headers);
        $this->assertContains('X-Frame-Options: SAMEORIGIN', $headers);
        $this->assertContains('X-Content-Type-Options: nosniff', $headers);
    }
}
