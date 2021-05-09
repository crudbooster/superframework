<?php

namespace App\Modules\Main\Controllers;

use SuperFrameworkEngine\Foundation\Controller;

/**
 * Class Home
 * @route /
 */
class HomeController extends Controller {

    /**
     * @return false|string
     * @route /
     * @throws \Exception
     */
    public function index()
    {
        return view("Main::home");
    }
}