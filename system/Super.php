<?php

namespace System;

class Super
{
    private $config;
    private $bootstrapCache;

    public function __construct()
    {
        $this->config = include base_path("configs/App.php");
        $this->bootstrapCache = include base_path("bootstrap/cache.php");
    }

    private function urlSlicing() {
        $args = explode('/', str_ireplace($this->config['base_url'], "", get_current_url([], false)));
        foreach($args as $i=>$arg) {
            if($arg == "index.php") {
                for($e=0;$e<=$i;$e++) unset($args[$e]);
                break;
            }
        }

        return array_filter(array_values($args));
    }

    private function controllerClassSelection($args) {
        if($args) {
            $class = $method = $param_start = $module_name = null;

            // Check to route file first
            $args_merge = implode("/", $args)."/";
            $args_count = count($args);
            $routes = include base_path("app/Configs/routes.php");
            foreach($routes as $path => $path_class) {
                $path .= "/";
                $total_split_path = count(explode("/", $path))-1;
                if($total_split_path == ($args_count-1) || $total_split_path == $args_count) {
                    if(substr($args_merge,0, strlen($path)) === $path) {
                        $class_split = explode("@", $path_class);
                        if($class_split) {
                            $class = $class_split[0];
                            $method = $class_split[1];
                            $param_start = count(explode("/", $path));
                        }
                    }
                }
            }


            if(!$class && !$method) {
                if(isset($args[0]) && isset($args[1]) && isset($args[2])) {
                    $class = "\App\Modules\\".ucfirst($args[0])."\\Controllers\\".ucfirst($args[1]);
                    $method = $args[2];
                    $param_start = 3;
                } elseif (isset($args[0]) && isset($args[1]) && !isset($args[2])) {
                    $class = "\App\Modules\\" . ucfirst($args[0]) . "\\Controllers\\" . ucfirst($args[1]);
                    $method = "index";
                    $param_start = 2;
                } elseif (isset($args[0]) && !isset($args[1]) && !isset($args[2])) {
                    $class = $method = $module_name = $param_start = null;
                }  else {
                    $class = $method = $module_name = $param_start = null;
                }
            }

        } else {
            $class = $this->config['default_controller'];
            $method = "index";
            $param_start = 1;
        }

        $obj = new \stdClass();
        $obj->method = $method;
        $obj->class = $class;
        $obj->param_start = $param_start;
        return $obj;
    }

    private function responseBuilder($selection, $args) {
        $class = $selection->class;
        if(method_exists($selection->class, $selection->method)) {
            // Get method params
            $args = array_slice($args, $selection->param_start-1);
            $args = array_values($args);
            if(count($args)==1) {
                $response = (new $class())->{$selection->method}($args[0]);
            } elseif (count($args) == 2) {
                $response = (new $class())->{$selection->method}($args[0], $args[1]);
            } elseif (count($args) == 3) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2]);
            } elseif (count($args) == 4) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2], $args[3]);
            } elseif (count($args) == 5) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2], $args[3], $args[4]);
            } else {
                $response = (new $class())->{$selection->method}();
            }
        } else {
            http_response_code(404);
            $response = rtrim(include "Views/error/404.php","1");
        }

        return $response;
    }


    public function run() {

        ini_set("display_errors",0);
        ini_set("display_startup_errors", 0);
        ini_set("error_log", base_path("error.log"));

        $args = $this->urlSlicing();
        $selection = $this->controllerClassSelection($args);

        try {
            echo call_user_func($response);
        } catch (\Throwable $e) {
            http_response_code(500);
            logging($e);
            exit;
        }
    }

}