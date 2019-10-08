<?php

namespace App\Modules\Site\Controllers;

use System\Controllers\Controller;

/**
 * Class Home
 * @package App\Modules\Site\Controllers
 * @route house
 */
class Home extends Controller {

    /**
     * @return false|string
     * @route front
     */
    public function index()
    {
        return view("site.home", [], 5);
    }
}