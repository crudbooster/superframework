<?php

namespace App\Modules\Main\Controllers;

use App\Repositories\UserRepository;
use App\Services\WelcomeService;
use SuperFrameworkEngine\Foundation\Controller;

/**
 * Class Home
 * @route /
 */
class HomeController extends Controller
{
    private WelcomeService $welcomeService;
    private UserRepository $userRepository;

    public function __construct(WelcomeService $welcomeService, UserRepository $userRepository)
    {
        $this->welcomeService = $welcomeService;
        $this->userRepository = $userRepository;
    }

    /**
     * @return false|string
     * @route /
     * @throws \Exception
     */
    public function index()
    {
        $message = $this->welcomeService->getWelcomeMessage();
        $users = $this->userRepository->all();

        return view("Main::home", compact('message', 'users'));
    }
}
