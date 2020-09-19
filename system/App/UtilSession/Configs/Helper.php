<?php

if(!function_exists('csrf_validation')) {
    function csrf_validation($param = '_token') {
        if(session('csrf_token') == request($param)) {
            session_forget('csrf_token');
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('csrf_token')) {
    function csrf_token() {
        if($exist = session('csrf_token')) {
            return $exist;
        } else {
            $token = md5(time());
            session(['csrf_token'=>$token]);
            return $token;
        }
    }
}


if(!function_exists("session")) {
    /**
     * @param string|array $data
     * @return mixed
     */
    function session($data) {
        if(is_array($data)) {
            foreach($data as $key=>$value) {
                $_SESSION[$key] = $value;
            }
            return true;
        } else {
            return isset($_SESSION[$data])?$_SESSION[$data]:null;
        }
    }
}

if(!function_exists("session_forget")) {
    /**
     * @param string $key
     */
    function session_forget($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}

if(!function_exists("session_flash")) {
    /**
     * @param null|array $data
     * @return mixed
     */
    function session_flash($data = null) {
        if(is_array($data)) {
            session(["session_flash"=>$data]);
        } else {
            $flash = session("session_flash");
            session_forget("session_flash");
            return $flash;
        }
    }
}