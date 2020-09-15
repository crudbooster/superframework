<?php

namespace System\App\UtilResponse;


class Response
{
    public function json($array, $cache_minutes = null) {
        header("Content-Type: application/json");

        $hash = md5("json".get_current_url().serialize($_REQUEST));

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