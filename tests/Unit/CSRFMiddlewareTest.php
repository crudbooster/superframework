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
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Manual session set to be absolutely sure
        $token = 'test_token';
        $_SESSION['csrf_token'] = $token;
        $_POST['_token'] = $token;
        $_REQUEST['_token'] = $token;
        
        $middleware = new CSRFMiddleware();
        $next = function() { return 'Passed'; };
        
        $result = $middleware->handle($next);
        $this->assertEquals('Passed', $result);
    }
}
