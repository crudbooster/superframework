<?php

namespace App\Middleware;

use Closure;
use System\Interfaces\Middleware;

class AppMiddleware implements Middleware
{

    /**
     * @param Closure $next
     * @return Closure
     */
    public function handle(Closure $next) {
        return $next;
    }

}