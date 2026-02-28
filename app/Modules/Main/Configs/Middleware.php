<?php

namespace App\Modules\Main\Configs;

use App\Middleware\CSRFMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\SecurityMiddleware;
use Closure;
use SuperFrameworkEngine\Interfaces\Middleware as BaseMiddleware;

class Middleware implements BaseMiddleware
{
    /**
     * @param Closure $next
     * @return mixed
     */
    public function handle(Closure $next)
    {
        $middlewares = [
            SecurityMiddleware::class,
            CSRFMiddleware::class,
            RateLimitMiddleware::class,
        ];

        $nextMiddleware = $next;

        foreach (array_reverse($middlewares) as $middlewareClass) {
            $instance = \SuperFrameworkEngine\Foundation\Container::getInstance()->make($middlewareClass);
            $currentNext = $nextMiddleware;
            $nextMiddleware = function () use ($instance, $currentNext) {
                return $instance->handle($currentNext);
            };
        }

        return $nextMiddleware();
    }
}
