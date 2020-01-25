<?php

namespace App\Modules\AdminAPI\Controllers;

use App\Models\Users;
use System\Controllers\Controller;

/**
 * @route admin-api/profile
 */
class ApiProfileController extends Controller {

    /**
     * @route update
     */
    public function update()
    {
        try {
            validate_required(['name', 'email']);

            $row = Users::findById(auth()->id());
            $row->setName(request_string('name'));
            $row->setEmail(request_email('email'));

            if(request_string('password')) {
                $row->setPassword(hashing_password(request_string('password')));
            }

            $row->save();

            return json(['message'=>'Your profile data has been updated!']);

        } catch (\Exception $e) {
            http_response_code(400);
            return json(['message'=>$e->getMessage()]);
        }
    }
}