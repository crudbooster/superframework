<?php

$singleton_data = [];

if(!function_exists("put_singleton")) {
    function put_singleton($key, $value) {
        global $singleton_data;
        $singleton_data[$key] = $value;
    }
}

if(!function_exists("get_singleton")) {
    function get_singleton($key) {
        global $singleton_data;
        return @$singleton_data[$key];
    }
}

if(!function_exists("request_url_is")) {
    /**
     * To detect if current url is contain specific asterisk
     * @param $path
     * @return bool
     */
    function request_url_is($path) {
        if(substr($path,-1,1)=="*") {
            $pattern = str_replace("*","(.*)", $path);
            $pattern = str_replace("/","\/", $pattern);
            $pattern = '/(.*)('.$pattern.'|'.rtrim($path,"*").')$/';
        } else {
            $pattern = str_replace("*","(.*)", $path);
            $pattern = str_replace("/","\/", $pattern);
            $pattern = '/(.*)'.$pattern.'$/';
        }

        if(preg_match($pattern, get_current_url()) === 1) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists("convert_snake_to_CamelCase")) {
    function convert_snake_to_CamelCase($snake_case_string, $capitalise_first_char = false) {
        $str = str_replace(' ','',ucwords(str_replace(['-',' ','_'], ' ', $snake_case_string)));
        if (!$capitalise_first_char) {
            $str = lcfirst($str);
        }
        return $str;
    }
}

if(!function_exists("convert_UpperCamel_to_snake")) {
    /**
     * @param $UpperCamel
     * @param string $separator
     * @return string
     */
    function convert_UpperCamel_to_snake($UpperCamel, $separator = "-") {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', $separator.'$0', $UpperCamel));
    }
}

if(!function_exists("url")) {
    /**
     * @param null $path
     * @return string
     */
    function url($path = null) {
        return base_url($path);
    }
}

if(!function_exists("auth")) {
    /**
     * To use this auth helper you need to create "users" table
     * CREATE TABLE IF NOT EXISTS `users` (
     * id INTEGER NOT NULL AUTOINCREMENT,
     * name VARCHAR(255) NOT NULL,
     * email VARCHAR(55) NOT NULL,
     * password VARCHAR(50) NOT NULL
     * )
     *
     * @return \System\Helpers\Auth
     */
    function auth() {
        return (new \System\Helpers\Auth());
    }
}

if(!function_exists("get_string_between")) {
    /**
     * @param $string
     * @param $start_string
     * @param $end_string
     * @return string
     */
    function get_string_between($string, $start_string, $end_string) {
        $r = explode($start_string, $string);
        if (isset($r[1])){
            $r = explode($end_string, $r[1]);
            return $r[0];
        }
        return '';
    }
}

if(!function_exists("alert_html")) {
    function alert_html() {
        $message = session_flash();
        if(isset($message)) {
            if($message['message'] && $message['type']) {
                return "<div class='alert alert-".$message['type']."'>".$message['message']."</div>";
            }
        }
        return null;
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

if(!function_exists("redirect_back")) {
    /**
     * @param array $with_session_data
     */
    function redirect_back($with_session_data = []) {
        if($with_session_data) {
            session_flash($with_session_data);
        }

        header("location: ".$_SERVER['HTTP_REFERER'], false, 301);
        exit;
    }
}

if(!function_exists("redirect")) {
    /**
     * @param $path
     * @param array $with_session_data
     */
    function redirect($path, $with_session_data = []) {
        if($with_session_data) {
            session_flash($with_session_data);
        }

        header("location: ".base_url($path), false, 301);
        exit;
    }
}


if(!function_exists("upload_image")) {
    /**
     * @param $input_name
     * @param $new_file_name
     * @return null|string
     */
    function upload_image($input_name, $new_file_name) {
        if(isset($_FILES[$input_name]["tmp_name"])) {
            if(!file_exists(getcwd()."/uploads")) {
                mkdir(getcwd()."/uploads");
            }

            if(!file_exists(getcwd()."/uploads/".date("Y-m-d"))) {
                mkdir(getcwd()."/uploads/".date("Y-m-d"));
            }

            $ext = pathinfo($_FILES[$input_name]['name'],PATHINFO_EXTENSION);

            $check = getimagesize($_FILES[$input_name]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES[$input_name]["tmp_name"], getcwd()."/uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext)) {
                    return "uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext;
                }
            }
        }
        return null;
    }
}

if(!function_exists("upload_file")) {
    /**
     * @param $input_name
     * @param $new_file_name
     * @return null|string
     */
    function upload_file($input_name, $new_file_name) {
        if(isset($_FILES[$input_name]["tmp_name"])) {
            if(!file_exists(getcwd()."/uploads")) {
                mkdir(getcwd()."/uploads");
            }

            if(!file_exists(getcwd()."/uploads/".date("Y-m-d"))) {
                mkdir(getcwd()."/uploads/".date("Y-m-d"));
            }

            $ext = pathinfo($_FILES[$input_name]['name'],PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES[$input_name]["tmp_name"], getcwd()."/uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext)) {
                return "uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext;
            }
        }
        return null;
    }
}

if(!function_exists("abort")) {
    /**
     * @param int $response_code
     * @param null $message
     */
    function abort($response_code = 404, $message = null) {
        http_response_code($response_code);
        include getcwd()."/system/Views/error/any.php";
        exit;
    }
}

if(!function_exists("logging")) {
    /**
     * @param $content
     * @param string $type
     */
    function logging($content, $type = "error") {
        file_put_contents(getcwd()."/system/Logs/".date("Y-m-d").".log", "[".date("Y-m-d H:i:s")."][".$type."] - ".$content."\n\n", FILE_APPEND);
    }
}

if(!function_exists("var_min_export")) {
    /**
     * @param $expression
     * @param bool $return
     * @return mixed|null|string|string[]
     */
    function var_min_export($expression, $return=FALSE) {
        $export = var_export($expression, TRUE);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
        $export = join(PHP_EOL, array_filter(["["] + $array));
        $export = preg_replace("/[0-9]+ \=\>/i", '', $export);
        if ((bool)$return) return $export; else echo $export;
    }
}

if(!function_exists("string_random")) {
    /**
     * @param int $length
     * @return string
     */
    function string_random($length = 6) {
        $random = bin2hex(openssl_random_pseudo_bytes($length, $cstrong));
        return $random;
    }
}

if(!function_exists("csrf_validation")) {
    function csrf_validation() {
        if(request_string("_token")) {
            if(cache("csrf_".request_string("_token"))) {
                return true;
            }
        }
        return false;
    }
}

if(!function_exists("csrf_input")) {
    function csrf_input() {
        $hash = string_random();
        cache("csrf_".$hash, $hash, 4320);
        return "<input type='hidden' name='_token' value='".$hash."'/>\n";
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

if(!function_exists("base_path")) {
    /**
     * @param null $path
     * @return string
     */
    function base_path($path = null) {
        return BASE_PATH."/".$path;
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

if(!function_exists('cache_forget')) {
    /**
     * Forget the existing cache file
     * @param $key
     */
    function cache_forget($key) {
        $file_path = getcwd()."/system/Caches/".md5($key);
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
}

if(!function_exists("cache")) {
    /**
     * Create or retrieve a cache
     * @param $key
     * @param null $value
     * @param int $minutes
     * @return null|string|mixed
     */
    function cache($key, $value = null, $minutes = 60) {
        $key = md5($key);
        if(file_exists(getcwd()."/system/Caches/".$key) && !$value) {
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
        $response = json_encode($array);

        if($cache_minutes) {
            cache($hash, $response, $cache_minutes);
        }

        return $response;
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
     * @param array|null $param
     * @param boolean $with_query
     * @return string
     */
    function get_current_url($param = [], $with_query = true) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = strtok($url,"?");

        if($with_query) {
            $param_array = array_merge($_GET, $param);
            $param_string = http_build_query($param_array);
            $param_string = ($param_string)?"?".$param_string:"";
            $url .= $param_string;
        }

        return $url;
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
        $value = $_REQUEST;
        $value = (isset($value[$name]))?$value[$name]:$default;
        $value = filter_var($value, FILTER_SANITIZE_URL);
        return (filter_var($value, FILTER_VALIDATE_URL))?$value:null;
    }
}

if(!function_exists("request_int")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_int($name, $default = null) {
        $value = $_REQUEST;
        $value = (isset($value[$name]))?$value[$name]:$default;
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return (filter_var($value, FILTER_VALIDATE_INT))?$value:null;
    }
}

if(!function_exists("request_string")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_string($name, $default = null) {
        $value = $_REQUEST;
        $value = (isset($value[$name]))?$value[$name]:$default;
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
        $value = $_REQUEST;
        $value = (isset($value[$name]))?$value[$name]:$default;
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        return (filter_var($value, FILTER_VALIDATE_EMAIL))?$value:null;
    }
}

if(!function_exists("request_float")) {
    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    function request_float($name, $default = null) {
        $value = $_REQUEST;
        $value = (isset($value[$name]))?$value[$name]:$default;
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        return (filter_var($value, FILTER_VALIDATE_FLOAT))?$value:null;
    }
}