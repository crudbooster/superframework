<?php

namespace System\App\UtilSession\Configs;

use System\Interfaces\BootInterface;

class Boot implements BootInterface
{
    /**
     * @throws \Exception
     */
    public function run() {
        session_start();
        session_name('sess_'.config('app_name'));
        session_set_cookie_params(config('session_lifetime'), '/', $_SERVER['HTTP_HOST'], config('session_secure'), config('session_httpOnly'));

        $this->csrfValidation();
    }

    /**
     * @throws \Exception
     */
    private function csrfValidation() {
        if(request_is_post()) {
            if(config("csrf_token") === true) {
                if(!request_url_is(config('csrf_token_ignore'))) {
                    if(!csrf_validation()) {
                        throw new \Exception("Invalid CSRF Token!", 403);
                    }
                }
            }
        }
    }
}