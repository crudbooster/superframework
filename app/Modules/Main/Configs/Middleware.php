<?php

namespace App\Modules\Main\Configs;

use Closure;
use SuperFrameworkEngine\Interfaces\Middleware as BaseMiddleware;

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