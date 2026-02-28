<?php

namespace App\Middleware;

use Closure;
use SuperFrameworkEngine\Interfaces\Middleware;

class CSRFMiddleware implements Middleware
{
    public function handle(Closure $next)
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            if (!csrf_validation()) {
                throw new \Exception("CSRF token is invalid!", 403);
            }
        }

        return $next();
    }
}
