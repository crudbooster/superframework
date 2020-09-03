<?php

if(!function_exists("db")) {
    /**
     * @param $table
     * @return \App\UtilORM\ORM
     */
    function db($table) {
        return (new \App\UtilORM\ORM())->db($table);
    }
}