<?php

namespace App\Main\Controllers;

use System\Controllers\Controller;

/**
 * Class Home
 * @package App\Modules\Site\Controllers
 * @route home
 */
class Home extends Controller {

    /**
     * @return false|string
     * @route index
     * @throws \Exception
     */
    public function index()
    {
        return view("Main.home");
    }
}