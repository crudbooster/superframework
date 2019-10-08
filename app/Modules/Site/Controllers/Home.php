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

    /**
     * @param $param
     * @route detail/foo/bar
     */
    public function detail($param) {
        $users = DB("users")->all(1000);
        dd($users);
    }
}