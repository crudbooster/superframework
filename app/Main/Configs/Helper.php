<?php
/**
 * Todo : Make your own helper function here
 */

if(!function_exists("hashing_password")) {
    function hashing_password($password_string) {
        return password_hash(config('password_salt').$password_string, PASSWORD_BCRYPT);
    }
}
