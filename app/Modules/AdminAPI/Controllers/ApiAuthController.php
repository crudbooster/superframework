<?php

namespace App\Modules\AdminAPI\Controllers;

use System\Controllers\Controller;

/**
 * @route admin-api/auth
 */
class ApiAuthController extends Controller {

    /**
     * @route login
     */
    public function login()
    {
        if(request_is_post()) {
            try {
                validate_required(['email', 'password']);

                if(auth()->attempt(request_email('email'), request_string('password'))) {
                    return json(['message'=>'success']);
                } else {
                    http_response_code(400);
                    return json(['message'=>'Please check your email and or password!']);
                }

            } catch (\Exception $e) {
                http_response_code(400);
                return json(['message'=>$e->getMessage()]);
            }
        } else {
            http_response_code(403);
            return json(['message'=>'Method not allowed!']);
        }
    }

    /**
     * @route check-session
     */
    public function checkSession() {
        if(auth()->id()) {
            return json(['message'=>'success','data'=>[
                'id'=> auth()->id(),
                'name'=> auth()->user()->getName(),
                'email'=> auth()->user()->getEmail()
            ]]);
        }

        http_response_code(401);
        return json(['message'=>'Please login first!']);
    }


    /**
     * @route logout
     */
    public function logout() {
        auth()->logout();
        return json(['message'=>'You have been log out!']);
    }
}