<?php

namespace System\App\UtilString\Configs;

use Closure;
use System\Interfaces\Middleware as BaseMiddleware;

class Middleware implements BaseMiddleware
{

    /**
     * @param Closure $next
     * @return mixed
     */
    public function handle(Closure $next) {
        return $next();
    }

}