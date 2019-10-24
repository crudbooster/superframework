<?php

namespace System\Commands;


class Command
{

    /**
     * @param $class_file
     * @return mixed|string
     */
    public function makeClassName($class_file) {
        $class_name = str_replace(base_path(),"",$class_file);
        $class_name = str_replace("/","\\", $class_name);
        $class_name = str_replace("app\\","App\\", $class_name);
        $class_name = str_replace(".php","", $class_name);
        return $class_name;
    }

}