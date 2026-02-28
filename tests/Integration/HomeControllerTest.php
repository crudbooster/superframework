<?php

namespace Tests\Integration;

use App\Modules\Main\Controllers\HomeController;
use App\Repositories\UserRepository;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_page_shows_welcome_message_and_users()
    {
        // Mock UserRepository to avoid DB issues in this environment
        $mockRepo = $this->createMock(UserRepository::class);
        $mockRepo->method('all')->willReturn([
            ['name' => 'Ferry', 'email' => 'ferry@example.com']
        ]);
        $this->app->singleton(UserRepository::class, fn() => $mockRepo);

        $controller = $this->app->make(HomeController::class);
        $response = $controller->index();

        $this->assertStringContainsString('Welcome to Super Framework!', $response);
        $this->assertStringContainsString('Ferry', $response);
    }
}
