<?php

namespace App\Components\AdminAuth\Controllers;

use System\Foundation\Controller;

/**
 * Class Home
 * @route /admin/auth
 */
class AdminAuthController extends Controller {

    /**
     * @return false|string
     * @route login
     * @throws \Exception
     */
    public function login()
    {
        $data = [];
        $data['title'] = "Login Area";
        return view("AdminAuth::login", $data);
    }
}