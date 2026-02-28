<?php

namespace App\Providers;

use App\Contracts\WelcomeStrategyInterface;
use App\Middleware\CSRFMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\SecurityMiddleware;
use App\Repositories\UserRepository;
use App\Services\DefaultWelcomeStrategy;
use App\Services\RedisService;
use SuperFrameworkEngine\App\UtilORM\ORM;
use SuperFrameworkEngine\Foundation\Container;
use SuperFrameworkEngine\Interfaces\BootInterface;

class AppServiceProvider implements BootInterface
{
    public function run()
    {
        $container = Container::getInstance();

        // Bind Redis as singleton
        $container->singleton(RedisService::class, function () {
            return new RedisService();
        });

        // Bind ORM as singleton
        $container->singleton(ORM::class, function () {
            return ORM::createConnection();
        });

        // Bind Repositories
        $container->singleton(UserRepository::class, function ($app) {
            return new UserRepository($app->make(ORM::class));
        });

        // Bind Middlewares
        $container->bind(SecurityMiddleware::class, SecurityMiddleware::class);
        $container->bind(CSRFMiddleware::class, CSRFMiddleware::class);
        $container->bind(RateLimitMiddleware::class, RateLimitMiddleware::class);

        // Bind Strategies
        $container->bind(WelcomeStrategyInterface::class, DefaultWelcomeStrategy::class);
    }
}
