<?php

$singleton_data = [];
if(!function_exists("put_singleton")) {
    function put_singleton($key, $value) {
        global $singleton_data;
        if(extension_loaded('apcu')) {
            apcu_add($key, $value, 5);
        } else {
            $singleton_data[$key] = $value;
        }
    }
}

if(!function_exists("get_singleton")) {
    function get_singleton($key) {
        global $singleton_data;
        if(extension_loaded('apc')) {
            return @apcu_fetch($key);
        } else {
            return @$singleton_data[$key];
        }
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

if(!function_exists("asset")) {
    /**
     * @param null $path
     * @return string
     */
    function asset($path = null) {
        return base_url($path);
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

        $url = strpos($path,'http')!==false?$path:base_url($path);
        header("location: ".$url, false, 301);
        exit;
    }
}

if(!function_exists("logging")) {
    /**
     * @param $content
     * @param string $type
     */
    function logging($content, $type = "error") {
        file_put_contents(base_path("/logs/".date("Y-m-d").".log"), "[".date("Y-m-d H:i:s")."][".$type."] - ".$content."\n\n", FILE_APPEND);
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

if(!function_exists("config")) {
    /**
     * @param $name
     * @return mixed
     */
    function config($name) {
        $name = strpos($name,".")!==false?$name:"default.".$name;
        $split_name = explode(".", $name);
        if($config_data = get_singleton("config_".$split_name[0])) {
            return $config_data[$split_name[1]];
        } else {
            if($split_name[0] == "default") {
                $config_data = include base_path("configs/App.php");
            } else {
                $config_data = include base_path("app/Components/".$split_name[0]."/Configs/App.php");
            }
            put_singleton("config_".$split_name[0], $config_data);
            $key = $split_name[1];
            return $config_data[$key];
        }
    }
}

if(!function_exists("public_path")) {
    /**
     * @param null $path
     * @return string
     */
    function public_path($path = null) {
        return BASE_PATH."/public/".$path;
    }
}

if(!function_exists("base_path")) {
    /**
     * @param null $path
     * @return string
     */
    function base_path($path = null) {
        return BASE_PATH.DIRECTORY_SEPARATOR.$path;
    }
}

if(!function_exists('base_path_uri')) {
    function base_path_uri($path = null) {
        $tmpURL = BASE_DIR;

        $tmpURL = str_replace(chr(92),'/',$tmpURL);

        $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);

        $tmpURL = ltrim($tmpURL,'/');
        $tmpURL = rtrim($tmpURL, '/');

        return ($path)?$tmpURL."/".$path:$tmpURL;
    }
}

if(!function_exists("base_url")) {
    /**
     * @param null $path
     * @param null $default
     * @return string
     */
    function base_url($path = null, $default = null) {
        $base_url = (isset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';

        $tmpURL = BASE_DIR;

        $tmpURL = str_replace(chr(92),'/',$tmpURL);

        $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);

        $tmpURL = ltrim($tmpURL,'/');
        $tmpURL = rtrim($tmpURL, '/');

        if ($tmpURL !== $_SERVER['HTTP_HOST']) {
            $base_url .= ($tmpURL) ? $_SERVER['HTTP_HOST'].'/'.$tmpURL.'/' : $_SERVER['HTTP_HOST'].'/';
        } else {
            $base_url .= $tmpURL.'/';
        }

        return ($path)?$base_url.$path:$base_url.$default;
    }
}



if(!function_exists('response')) {
    /**
     * @return \System\App\UtilResponse\Response
     */
    function response() {
        return (new \System\App\UtilResponse\Response());
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