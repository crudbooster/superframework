<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Middleware\CSRFMiddleware;
use Closure;

class CSRFMiddlewareTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function test_get_request_passes_csrf()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $middleware = new CSRFMiddleware();
        $next = function() { return 'Passed'; };

        $result = $middleware->handle($next);
        $this->assertEquals('Passed', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_post_request_fails_without_csrf()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('CSRF token is invalid!');
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['_token'] = 'invalid';
        
        $middleware = new CSRFMiddleware();
        $next = function() { return 'Passed'; };
        
        $middleware->handle($next);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_post_request_passes_with_valid_csrf()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $token = csrf_token();
        $_POST['_token'] = $token;
        
        $middleware = new CSRFMiddleware();
        $next = function() { return 'Passed'; };
        
        $result = $middleware->handle($next);
        $this->assertEquals('Passed', $result);
    }
}
