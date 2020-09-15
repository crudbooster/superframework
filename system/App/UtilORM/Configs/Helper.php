<?php

if(!function_exists("db")) {
    /**
     * @param $table
     * @return \System\App\UtilORM\ORM
     */
    function db($table) {
        return (new \System\App\UtilORM\ORM())->db($table);
    }
}