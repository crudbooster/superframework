<?php

if(!function_exists('cache_forget')) {
    /**
     * Forget the existing cache file
     * @param $key
     */
    function cache_forget($key) {
        $file_path = base_path("system/App/UtilCache/Cache/".md5($key));
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
        if(file_exists(base_path("system/App/UtilCache/Cache/".$key)) && !$value) {
            $cache = file_get_contents(base_path("system/App/UtilCache/Cache/".$key));
            $cache = json_decode($cache, true);
            if($cache['expired'] > time()) {
                return $cache['content'];
            }
        }

        if($value) {
            file_put_contents(base_path("system/App/UtilCache/Cache/".$key), json_encode([
                "expired"=>strtotime("+".$minutes." minutes"),
                "content"=>$value
            ]));
        }

        return null;
    }
}