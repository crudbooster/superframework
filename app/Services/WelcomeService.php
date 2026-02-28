<?php

namespace App\Services;

use App\Contracts\WelcomeStrategyInterface;

class WelcomeService
{
    private WelcomeStrategyInterface $strategy;

    public function __construct(WelcomeStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getWelcomeMessage(): string
    {
        return $this->strategy->getMessage();
    }
}
