<?php

namespace System\Commands;


class Command
{

    /**
     * @param $class_file
     * @return mixed|string
     */
    public function makeClassName($class_file) {
        $class_name = str_replace(getcwd(),"",$class_file);
        $class_name = str_replace("/","\\", $class_name);
        $class_name = str_replace("\app","\App", $class_name);
        $class_name = rtrim($class_name, ".php");
        return $class_name;
    }

}