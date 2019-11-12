<?php

namespace App\Modules\Site\Controllers;

use System\Controllers\Controller;
use System\ORM\ORM;

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
        return view("site.home");
    }
}