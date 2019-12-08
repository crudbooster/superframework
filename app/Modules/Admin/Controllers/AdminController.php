<?php

namespace App\Modules\Admin\Controllers;

use System\Controllers\Controller;

/**
 * @route admin
 */
class AdminController extends Controller {

    /**
     * @route index
     */
    public function index()
    {
        return view("Admin.index");
    }
}