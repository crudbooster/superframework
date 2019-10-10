<?php

namespace App\Modules\Site\Middleware;

use Closure;
use System\Interfaces\Middleware;

class SiteMiddleware implements Middleware
{

    /**
     * @param Closure $next
     * @return Closure
     */
    public function handle(Closure $next) {
        return $next;
    }

}