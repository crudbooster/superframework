<?php


if(!function_exists("request_url_is")) {
    /**
     * To detect if current url is contain specific asterisk
     * @param array|string $path_param
     * @return bool
     */
    function request_url_is($path_param) {
        $paths = is_array($path_param)?$path_param : [ $path_param ];

        foreach($paths as $path) {
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
            }
        }
        return false;
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


if(!function_exists('request')) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return null
     */
    function request($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name])?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        return $value;
    }
}


if(!function_exists("request_url")) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_url($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name])?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        $value = filter_var($value, FILTER_SANITIZE_URL);
        return (filter_var($value, FILTER_VALIDATE_URL))?$value:null;
    }
}

if(!function_exists("request_int")) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_int($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name])?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return (filter_var($value, FILTER_VALIDATE_INT))?$value:null;
    }
}

if(!function_exists("request_string")) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_string($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name] && $value[$name] != "null")?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        return $value;
    }
}

if(!function_exists("request_email")) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_email($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name])?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        return (filter_var($value, FILTER_VALIDATE_EMAIL))?$value:null;
    }
}

if(!function_exists("request_float")) {
    /**
     * @param $name
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_float($name, $default = null, $sanitize = false) {
        $value = $_REQUEST;
        $value = (isset($value[$name]) && $value[$name])?$value[$name]:$default;
        $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        return (filter_var($value, FILTER_VALIDATE_FLOAT))?$value:null;
    }
}

if(!function_exists('request_json'))
{
    /**
     * @param null $key
     * @param null $default
     * @param bool $sanitize
     * @return mixed
     */
    function request_json($key = null, $default = null, $sanitize = false)
    {
        $json_data = file_get_contents('php://input');
        $input = json_decode($json_data, TRUE);
        if(isset($key)) {
            $value = isset($input[$key]) ? $input[$key] : $default;
            $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
            return $value;
        } else {
            return $input ? $input : null;
        }
    }
}

if(!function_exists('request_json_string'))
{
    /**
     * @param $key
     * @param null $default
     * @param bool $sanitize
     * @return mixed|null
     */
    function request_json_string($key, $default = null, $sanitize = false)
    {
        // Get Data Raw JSON
        $input = request_json($key);

        if($input) {
            $value = filter_var($input,FILTER_SANITIZE_STRING);
            $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
            return $value ? : $default;
        }
        return null;
    }
}


if(!function_exists('request_json_int'))
{
    /**
     * @param $key
     * @param null $default
     * @param bool $sanitize
     * @return null
     */
    function request_json_int($key, $default = null, $sanitize = false)
    {
        $input = request_json($key);

        if($input) {
            $value = filter_var($input,FILTER_SANITIZE_NUMBER_INT);
            $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
            return $value ? : $default;
        }
        return null;
    }
}


if(!function_exists('request_json_float'))
{
    /**
     * @param $key
     * @param null $default
     * @param bool $sanitize
     * @return null
     */
    function request_json_float($key, $default = null, $sanitize = false)
    {
        $input = request_json($key);

        if($input) {
            $value = filter_var($input,FILTER_SANITIZE_NUMBER_FLOAT);
            $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
            return $value ? : $default;
        }
        return null;
    }
}

if(!function_exists('request_json_email'))
{
    /**
     * @param $key
     * @param null $default
     * @param bool $sanitize
     * @return null
     */
    function request_json_email($key, $default = null, $sanitize = false)
    {
        $input = request_json($key);

        if($input) {
            $value = filter_var($input,FILTER_SANITIZE_EMAIL);
            $value = ($sanitize) ? htmlspecialchars($value, ENT_COMPAT, "UTF-8") : $value;
            return $value ? : $default;
        }
        return null;
    }
}