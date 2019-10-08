<?php

if(!function_exists("config")) {
    /**
     * @param $name
     * @return mixed
     */
    function config($name) {
        $name = strpos($name,".")!==false?$name:"config.".$name;
        $split_name = explode(".", $name);
        $config_data = include getcwd()."/app/Configs/".$split_name[0].".php";
        $key = $split_name[1];
        return $config_data[$key];
    }
}

if(!function_exists("base_url")) {
    /**
     * @param null $path
     * @return string
     */
    function base_url($path = null) {
        return config("base_url").$path;
    }
}

if(!function_exists("cache")) {
    /**
     * @param $key
     * @param null $value
     * @param int $minutes
     * @return null|string|mixed
     */
    function cache($key, $value = null, $minutes = 60) {
        $key = md5($key);
        if(file_exists(getcwd()."/system/Caches/".$key)) {
            $cache = file_get_contents(getcwd()."/system/Caches/".$key);
            $cache = json_decode($cache, true);
            if($cache['expired'] > time()) {
                return $cache['content'];
            }
        }

        if($value) {
            file_put_contents(getcwd()."/system/Caches/".$key, json_encode([
                "expired"=>strtotime("+".$minutes." minutes"),
                "content"=>$value
            ]));
        }

        return null;
    }
}

if(!function_exists("json")) {
    /**
     * @param array|callable $array
     * @param null $cache_minutes
     * @return false|string
     */
    function json($array, $cache_minutes = null) {
        header("Content-Type: application/json");

        $hash = md5("json".get_current_url().serialize($_GET));

        if($cache_minutes && $cache = cache($hash)) {
            $seconds_to_cache = 60 * $cache_minutes;
            $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
            header("Expires: $ts");
            header("Pragma: cache");
            header("Cache-Control: max-age=$seconds_to_cache, public");
            return $cache;
        }

        $array = is_callable($array)?call_user_func($array):$array;
        if($cache_minutes) {
            cache($hash, $array, $cache_minutes);
        }

        return json_encode($array);
    }
}

if(!function_exists("dd")) {
    /**
     * @param $array
     */
    function dd($array) {
        $arguments = func_get_args();
        foreach($arguments as $arg) {
            echo "<pre><code>";
            print_r($arg);
            echo "</code></pre>";
        }
        exit;
    }
}

if(!function_exists("DB")) {
    /**
     * @param $table
     * @return \System\ORM\ORM
     */
    function DB($table) {
        return (new System\ORM\ORM())->db($table);
    }
}

if(!function_exists("view")) {
    /**
     * @param string $view_name
     * @param array $data
     * @param int $cache_in_minutes
     * @return false|null|string
     */
    function view($view_name, $data = [], $cache_in_minutes = null) {
        if($cache_in_minutes && $cache = cache("view_".$view_name)) return $cache;
        ob_start();
        extract($data);
        $view_split = explode(".", $view_name);
        include getcwd()."/app/Modules/".ucfirst($view_split[0])."/Views/".$view_split[1].".php";
        $response = ob_get_clean();
        if($cache_in_minutes) cache("view_".$view_name, $response, $cache_in_minutes);
        return $response;
    }
}

if(!function_exists("get_header_content")) {
    /**
     * @param $key
     * @return null|string
     */
    function get_header_content($key) {
        $list = headers_list();
        foreach($list as $item) {
            if(stripos($item, $key)!==false) {
                return trim(str_replace($item.":","", $item));
            }
        }
        return null;
    }
}

if(!function_exists("get_current_url")) {
    /**
     * @return string
     */
    function get_current_url() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if(!function_exists("request_is_post")) {
    /**
     * @return bool
     */
    function request_is_post() {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }
}

if(!function_exists("request_is_get")) {
    /**
     * @return bool
     */
    function request_is_get() {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }
}


if(!function_exists("request_url")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_url($name, $default = null) {
        $value = array_merge($_GET, $_POST);
        $value = $value?:$default;
        $value = $value[$name];
        $value = filter_var($value, FILTER_SANITIZE_URL);
        return (filter_var($value, FILTER_VALIDATE_URL)===TRUE)?$value:null;
    }
}

if(!function_exists("request_int")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_int($name, $default = null) {
        $value = array_merge($_GET, $_POST);
        $value = $value?:$default;
        $value = $value[$name];
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return (filter_var($value, FILTER_VALIDATE_INT)===TRUE)?$value:null;
    }
}

if(!function_exists("request_string")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_string($name, $default = null) {
        $value = array_merge($_GET, $_POST);
        $value = $value?:$default;
        $value = $value[$name];
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        return $value;
    }
}

if(!function_exists("request_email")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_email($name, $default = null) {
        $value = array_merge($_GET, $_POST);
        $value = $value?:$default;
        $value = $value[$name];
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        return (filter_var($value, FILTER_VALIDATE_EMAIL)===TRUE)?$value:null;
    }
}

if(!function_exists("request_float")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_float($name, $default = null) {
        $value = array_merge($_GET, $_POST);
        $value = $value?:$default;
        $value = $value[$name];
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        return (filter_var($value, FILTER_VALIDATE_FLOAT)===TRUE)?$value:null;
    }
}