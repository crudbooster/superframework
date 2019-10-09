<?php

namespace App\Modules\Site\Middleware;

use Closure;

class SiteMiddleware
{

    /**
     * @param Closure $next
     * @return Closure
     */
    public function handle(Closure $next) {
        return $next;
    }

}