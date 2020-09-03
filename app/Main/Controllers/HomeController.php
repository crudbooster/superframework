<?php

namespace App\Main\Controllers;

use System\Foundation\Controller;

/**
 * Class Home
 * @route /
 */
class HomeController extends Controller {

    /**
     * @return false|string
     * @route index
     * @throws \Exception
     */
    public function index()
    {
        return view("Main::home");
    }
}