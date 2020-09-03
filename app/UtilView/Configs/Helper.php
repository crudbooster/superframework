<?php

if(!function_exists("view")) {
    /**
     * @param $view_name
     * @param array $data
     * @return string
     */
    function view($view_name, $data = []) {
        $view_split = explode("::",$view_name);
        $blade = new \Jenssegers\Blade\Blade(base_path("app/".$view_split[0]."/views"),base_path("app/UtilView/Cache"));
        return $blade->make($view_split[1],$data)->render();
    }
}