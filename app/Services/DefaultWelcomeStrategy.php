<?php

namespace App\Services;

use App\Contracts\WelcomeStrategyInterface;

class DefaultWelcomeStrategy implements WelcomeStrategyInterface
{
    public function getMessage(): string
    {
        return "Welcome to Super Framework!";
    }
}
