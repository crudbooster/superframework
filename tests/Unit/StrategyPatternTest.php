<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WelcomeService;
use App\Services\DefaultWelcomeStrategy;

class StrategyPatternTest extends TestCase
{
    public function test_default_welcome_strategy_returns_correct_message()
    {
        $strategy = new DefaultWelcomeStrategy();
        $this->assertEquals("Welcome to Super Framework!", $strategy->getMessage());
    }

    public function test_welcome_service_uses_strategy()
    {
        $strategy = new DefaultWelcomeStrategy();
        $service = new WelcomeService($strategy);
        $this->assertEquals("Welcome to Super Framework!", $service->getWelcomeMessage());
    }
}
