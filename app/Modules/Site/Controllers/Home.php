<?php

namespace App\Modules\Site\Controllers;

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
     */
    public function index()
    {
        return view("site.home");
    }
}