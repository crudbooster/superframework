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
        if(auth()->guest()) redirect(config("backend_path")."/login");
        return view("Admin.index");
    }

    /**
     * @route login
     */
    public function login()
    {
        if(request_is_post()) {
            try {
                validate_required(['email', 'password']);

                if(auth()->attempt(request_email('email'), request_string('password'))) {
                    redirect(config('backend_path'));
                } else {
                    redirect_back(['message'=>'Please check your email and or password!','type'=>'warning']);
                }

            } catch (\Exception $e) {
                redirect_back(['message'=>$e->getMessage(),'type'=>'warning']);
            }
        }

        return view("Admin.login");
    }
}