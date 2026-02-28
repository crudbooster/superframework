<?php

namespace App\Middleware;

use Closure;
use SuperFrameworkEngine\Interfaces\Middleware;

class SecurityMiddleware implements Middleware
{
    public function handle(Closure $next)
    {
        header("X-XSS-Protection: 1; mode=block");
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("Content-Security-Policy: default-src 'self'");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: geolocation=(), microphone=()");

        return $next();
    }
}
