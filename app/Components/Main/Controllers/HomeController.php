<?php

namespace App\Components\Main\Controllers;

use System\Foundation\Controller;

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

    /**
     * @route test
     */
    public function test() {
        echo "oke bisa";
    }
}