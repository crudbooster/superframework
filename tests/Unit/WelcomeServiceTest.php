<?php

namespace Tests\Unit;

use App\Contracts\WelcomeStrategyInterface;
use App\Services\WelcomeService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class WelcomeServiceTest extends TestCase
{
    public function test_get_welcome_message_returns_correct_string()
    {
        /** @var WelcomeStrategyInterface|MockObject $mockStrategy */
        $mockStrategy = $this->createMock(WelcomeStrategyInterface::class);
        $mockStrategy->method('getMessage')->willReturn('Mocked Welcome');

        $service = new WelcomeService($mockStrategy);
        $result = $service->getWelcomeMessage();

        $this->assertEquals('Mocked Welcome', $result, "The welcome message should match the mocked strategy message");
    }

    public function test_get_welcome_message_with_empty_string()
    {
        /** @var WelcomeStrategyInterface|MockObject $mockStrategy */
        $mockStrategy = $this->createMock(WelcomeStrategyInterface::class);
        $mockStrategy->method('getMessage')->willReturn('');

        $service = new WelcomeService($mockStrategy);
        $result = $service->getWelcomeMessage();

        $this->assertEquals('', $result, "Should handle empty message correctly");
    }
}
