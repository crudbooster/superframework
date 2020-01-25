<?php

namespace App\Modules\AdminAPI\Controllers;

use System\Controllers\Controller;

/**
 * @route admin-api/auth
 */
class ApiAuthController extends Controller {


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

}